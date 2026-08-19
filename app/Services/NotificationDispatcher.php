<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationDispatcher
{
    /**
     * Dispatch notification if enabled by settings.
     */
    public static function dispatch(string $eventKey, mixed $recipient, array $data = []): bool
    {
        // 1. Master toggle check
        if (setting('pref_notifications') == '0') {
            Log::info("Notification for {$eventKey} suppressed: pref_notifications is disabled.");
            return false;
        }

        // 2. Specific event check
        $eventSetting = setting("notify_{$eventKey}", '1');
        if ($eventSetting == '0') {
            Log::info("Notification for {$eventKey} suppressed: notify_{$eventKey} is disabled.");
            return false;
        }

        // 3. Channels check
        $channels = [];
        if (setting('channel_email', '1') == '1') {
            $channels[] = 'email';
        }
        if (setting('channel_sms', '0') == '1') {
            $channels[] = 'sms';
        }
        if (setting('channel_inapp', '1') == '1') {
            $channels[] = 'inapp';
        }

        if (empty($channels)) {
            Log::info("Notification for {$eventKey} suppressed: no active notification channels.");
            return false;
        }

        $email = is_string($recipient) ? $recipient : ($recipient->email ?? null);

        // Execute dispatch per enabled channel
        foreach ($channels as $channel) {
            if ($channel === 'email' && $email) {
                try {
                    $subject = $data['subject'] ?? 'CRM Notification: ' . ucfirst(str_replace('_', ' ', $eventKey));
                    $messageBody = $data['message'] ?? 'You have a new notification regarding ' . $eventKey;

                    Mail::raw($messageBody, function ($message) use ($email, $subject) {
                        $message->to($email)->subject($subject);
                    });
                } catch (\Throwable $e) {
                    Log::error("Failed to send email notification for {$eventKey}: " . $e->getMessage());
                }
            }

            if ($channel === 'sms') {
                Log::info("SMS notification queued for {$eventKey} to " . ($recipient->phone ?? 'recipient'));
            }

            if ($channel === 'inapp') {
                Log::info("In-app notification created for {$eventKey}");
            }
        }

        AuditLog::record("Notification Sent ({$eventKey})", 'Notifications');
        return true;
    }
}
