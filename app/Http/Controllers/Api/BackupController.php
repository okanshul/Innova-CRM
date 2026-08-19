<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use ZipArchive;

class BackupController extends Controller
{
    public function index()
    {
        Gate::authorize('settings.view');

        $backups = Backup::with('creator')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $backups
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('settings.edit');

        try {
            $backupDir = storage_path('app/backups');
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0777, true);
            }

            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.zip';
            $zipPath = $backupDir . '/' . $filename;

            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                // Add public uploads if exists
                $uploadsDir = public_path('uploads');
                if (file_exists($uploadsDir)) {
                    $files = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($uploadsDir),
                        \RecursiveIteratorIterator::LEAVES_ONLY
                    );

                    foreach ($files as $name => $file) {
                        if (!$file->isDir()) {
                            $filePath = $file->getRealPath();
                            $relativePath = 'uploads/' . substr($filePath, strlen($uploadsDir) + 1);
                            $zip->addFile($filePath, $relativePath);
                        }
                    }
                }

                // Add database dump / settings snapshot
                $settingsJson = json_encode(\App\Models\CrmSetting::getAllSettings(), JSON_PRETTY_PRINT);
                $zip->addFromString('settings_snapshot.json', $settingsJson);

                $zip->close();
            }

            $size = file_exists($zipPath) ? filesize($zipPath) : 0;

            $backup = Backup::create([
                'filename' => $filename,
                'path' => 'backups/' . $filename,
                'size' => $size,
                'created_by' => Auth::id(),
            ]);

            AuditLog::record('Created', 'Backup');

            return response()->json([
                'success' => true,
                'message' => 'New system backup created successfully.',
                'data' => $backup->load('creator')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create backup: ' . $e->getMessage()
            ], 500);
        }
    }

    public function restore(Request $request)
    {
        Gate::authorize('settings.edit');

        $request->validate([
            'backup_file' => 'required|file|mimes:zip|max:51200'
        ]);

        try {
            $file = $request->file('backup_file');
            $zip = new ZipArchive();
            if ($zip->open($file->getRealPath()) === true) {
                // Extract snapshot if exists
                $snapshot = $zip->getFromName('settings_snapshot.json');
                if ($snapshot) {
                    $settings = json_decode($snapshot, true);
                    if (is_array($settings)) {
                        foreach ($settings as $key => $val) {
                            \App\Models\CrmSetting::set($key, $val);
                        }
                    }
                }

                // Extract uploads
                $zip->extractTo(public_path());
                $zip->close();
            }

            AuditLog::record('Updated', 'Backup Restore');

            return response()->json([
                'success' => true,
                'message' => 'System backup restored successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore backup: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download($id)
    {
        Gate::authorize('settings.view');

        $backup = Backup::findOrFail($id);
        $fullPath = storage_path('app/' . $backup->path);

        if (!file_exists($fullPath)) {
            return response()->json(['message' => 'Backup file not found on server.'], 404);
        }

        return response()->download($fullPath, $backup->filename);
    }

    public function destroy($id)
    {
        Gate::authorize('settings.edit');

        $backup = Backup::findOrFail($id);
        $fullPath = storage_path('app/' . $backup->path);

        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }

        $backup->delete();

        AuditLog::record('Deleted', 'Backup');

        return response()->json([
            'success' => true,
            'message' => 'Backup deleted successfully.'
        ]);
    }
}
