<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Backup;
use App\Models\CrmSetting;
use App\Models\User;
use App\Services\NotificationDispatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Gate::before(fn () => true);

        $this->user = User::factory()->create(['status' => 'active', 'role' => 'Administrator']);
        $this->actingAs($this->user);
    }

    public function test_settings_can_be_saved_and_persisted(): void
    {
        $response = $this->postJson(route('crm.api.settings.store'), [
            'company_name' => 'Acme Test Corp',
            'system_email' => 'admin@acme.test',
            'deal_close_days' => 45,
            'primary_color' => '#123456',
            'sidebar_bg_color' => '#1A1E2E',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals('Acme Test Corp', CrmSetting::get('company_name'));
        $this->assertEquals('admin@acme.test', CrmSetting::get('system_email'));
        $this->assertEquals(45, CrmSetting::get('deal_close_days'));
        $this->assertEquals('#123456', CrmSetting::get('primary_color'));
        $this->assertEquals('#1A1E2E', CrmSetting::get('sidebar_bg_color'));
    }

    public function test_settings_can_be_reset_to_default_values(): void
    {
        CrmSetting::set('company_name', 'Custom Changed Name');
        CrmSetting::set('primary_color', '#990000');
        CrmSetting::set('sidebar_bg_color', '#009900');

        $response = $this->postJson(route('crm.api.settings.reset'));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals('InnovaCRM Inc.', CrmSetting::get('company_name'));
        $this->assertEquals('#5030FF', CrmSetting::get('primary_color'));
        $this->assertEquals('#0B0F19', CrmSetting::get('sidebar_bg_color'));
    }

    public function test_unchecked_checkboxes_are_saved_as_zero(): void
    {
        $response = $this->postJson(route('crm.api.settings.store'), [
            'pref_notifications' => '0',
            'channel_sms' => '0',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('0', CrmSetting::get('pref_notifications'));
        $this->assertEquals('0', CrmSetting::get('channel_sms'));
    }

    public function test_settings_validation_returns_error_json(): void
    {
        $response = $this->postJson(route('crm.api.settings.store'), [
            'system_email' => 'invalid-email-address',
            'primary_color' => 'not-a-hex-color',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['success', 'message', 'errors']);
    }

    public function test_maintenance_mode_blocks_non_admin_users(): void
    {
        CrmSetting::set('maintenance_mode', '1');

        $regularUser = User::factory()->create(['role' => 'Sales Executive']);

        $this->actingAs($regularUser);
        $response = $this->get('/settings');
        $response->assertStatus(503);

        // Admin still has access
        $this->actingAs($this->user);
        $adminResponse = $this->get('/settings');
        $adminResponse->assertStatus(200);
    }

    public function test_items_per_page_setting_applies_to_pagination(): void
    {
        CrmSetting::set('items_per_page', 5);

        User::factory()->count(10)->create();

        $response = $this->getJson(route('crm.api.contacts.index'));
        $response->assertStatus(200);
        $this->assertEquals(5, $response->json('data.per_page'));
    }

    public function test_formatters_apply_settings_formatting(): void
    {
        CrmSetting::set('currency_symbol', 'INR');
        CrmSetting::set('localization_number_format', '1,234.56');

        $this->assertEquals('₹1,500.50', format_currency(1500.5));
    }

    public function test_notification_dispatcher_gates_sending(): void
    {
        CrmSetting::set('pref_notifications', '0');

        $sent = NotificationDispatcher::dispatch('new_deal', $this->user, ['message' => 'Test']);
        $this->assertFalse($sent);

        CrmSetting::set('pref_notifications', '1');
        $sentActive = NotificationDispatcher::dispatch('new_deal', $this->user, ['message' => 'Test']);
        $this->assertTrue($sentActive);
    }

    public function test_test_email_endpoint_validates_smtp_credentials(): void
    {
        $response = $this->postJson(route('crm.api.settings.test_email'), []);
        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }

    public function test_backup_creation_and_listing(): void
    {
        $response = $this->postJson(route('crm.api.backups.store'));
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseCount('backups', 1);

        $listResponse = $this->getJson(route('crm.api.backups.index'));
        $listResponse->assertStatus(200);
        $listResponse->assertJsonCount(1, 'data');
    }

    public function test_user_crud_operations(): void
    {
        // 1. Create User
        $createResponse = $this->postJson(route('crm.api.users.store'), [
            'name' => 'Jane Tester',
            'email' => 'jane@example.com',
            'password' => 'secret123',
            'role' => 'Manager',
            'status' => 'active',
        ]);

        $createResponse->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
        $createdUserId = $createResponse->json('data.id');

        // 2. Update User
        $updateResponse = $this->putJson(route("crm.api.users.update", $createdUserId), [
            'name' => 'Jane Updated',
            'email' => 'jane@example.com',
            'role' => 'Administrator',
            'status' => 'active',
        ]);

        $updateResponse->assertStatus(200);
        $this->assertDatabaseHas('users', ['name' => 'Jane Updated', 'role' => 'Administrator']);

        // 3. Delete User
        $deleteResponse = $this->deleteJson(route("crm.api.users.destroy", $createdUserId));
        $deleteResponse->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $createdUserId]);
    }

    public function test_audit_logs_endpoint_filtering(): void
    {
        AuditLog::query()->delete();
        AuditLog::record('Created', 'TestModule');
        AuditLog::record('Deleted', 'TestModule');

        $response = $this->getJson(route('crm.api.audit_logs.index', ['action' => 'Created']));
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data.data');
        $this->assertEquals('Created', $response->json('data.data.0.action'));
    }

    public function test_system_info_endpoint(): void
    {
        $response = $this->getJson(route('crm.api.system_info.index'));
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'data' => ['php_version', 'laravel_version', 'db_driver']]);
    }
}
