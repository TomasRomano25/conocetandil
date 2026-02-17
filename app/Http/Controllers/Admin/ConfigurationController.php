<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ConfigurationController extends Controller
{
    public function index()
    {
        $backupDir     = storage_path('app/backups');
        $latestFile    = Configuration::get('backup_latest_file');
        $latestSize    = null;
        $backupCount   = 0;

        if ($latestFile && file_exists($backupDir . '/' . $latestFile)) {
            $latestSize = round(filesize($backupDir . '/' . $latestFile) / 1024, 1);
        } else {
            $latestFile = null;
        }

        $existing = glob($backupDir . '/backup-*.sqlite') ?: [];
        $backupCount = count($existing);

        $config = [
            'backup_enabled'        => Configuration::get('backup_enabled', '1'),
            'backup_interval_hours' => Configuration::get('backup_interval_hours', '1'),
            'backup_keep_count'     => Configuration::get('backup_keep_count', '10'),
            'backup_last_run'       => Configuration::get('backup_last_run'),
        ];

        return view('admin.configuraciones.index', compact('config', 'latestFile', 'latestSize', 'backupCount'));
    }

    public function updateBackup(Request $request)
    {
        $request->validate([
            'backup_interval_hours' => 'required|integer|in:1,2,4,6,12,24',
            'backup_keep_count'     => 'required|integer|in:5,10,20,30',
        ]);

        Configuration::set('backup_enabled',        $request->boolean('backup_enabled') ? '1' : '0');
        Configuration::set('backup_interval_hours', $request->input('backup_interval_hours'));
        Configuration::set('backup_keep_count',     $request->input('backup_keep_count'));

        return redirect()->route('admin.configuraciones.index')
            ->with('success', 'Configuración guardada correctamente.');
    }

    public function runBackup()
    {
        Artisan::call('db:backup', ['--force' => true]);
        $output = trim(Artisan::output());

        $message = $output ?: 'Backup ejecutado correctamente.';

        return redirect()->route('admin.configuraciones.index')
            ->with('success', $message);
    }

    public function downloadBackup()
    {
        $filename  = Configuration::get('backup_latest_file');
        $path      = storage_path('app/backups/' . $filename);

        if (! $filename || ! file_exists($path)) {
            return redirect()->route('admin.configuraciones.index')
                ->with('error', 'No hay ningún backup disponible para descargar.');
        }

        return response()->download($path, $filename);
    }
}
