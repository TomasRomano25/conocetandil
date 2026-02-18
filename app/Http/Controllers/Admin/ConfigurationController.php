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

        $smtp = [
            'host'       => Configuration::get('smtp_host', ''),
            'port'       => Configuration::get('smtp_port', '587'),
            'encryption' => Configuration::get('smtp_encryption', 'tls'),
            'username'   => Configuration::get('smtp_username', ''),
            'from_email' => Configuration::get('smtp_from_email', ''),
            'from_name'  => Configuration::get('smtp_from_name', 'Conoce Tandil'),
        ];

        $payment = [
            'bank_name'           => Configuration::get('bank_name', ''),
            'bank_account_holder' => Configuration::get('bank_account_holder', ''),
            'bank_cbu'            => Configuration::get('bank_cbu', ''),
            'bank_alias'          => Configuration::get('bank_alias', ''),
            'bank_account_number' => Configuration::get('bank_account_number', ''),
            'bank_instructions'   => Configuration::get('bank_instructions', ''),
        ];

        return view('admin.configuraciones.index', compact('config', 'latestFile', 'latestSize', 'backupCount', 'smtp', 'payment'));
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

    public function updatePayment(Request $request)
    {
        $request->validate([
            'bank_name'            => 'nullable|string|max:100',
            'bank_account_holder'  => 'nullable|string|max:255',
            'bank_cbu'             => 'nullable|string|max:22',
            'bank_alias'           => 'nullable|string|max:100',
            'bank_account_number'  => 'nullable|string|max:100',
            'bank_instructions'    => 'nullable|string|max:1000',
        ]);

        $fields = ['bank_name', 'bank_account_holder', 'bank_cbu', 'bank_alias', 'bank_account_number', 'bank_instructions'];
        foreach ($fields as $field) {
            Configuration::set($field, $request->input($field) ?? '');
        }

        return redirect()->route('admin.configuraciones.index')
            ->with('success', 'Datos de transferencia bancaria guardados.');
    }

    public function updateSmtp(Request $request)
    {
        $request->validate([
            'smtp_host'       => 'nullable|string|max:255',
            'smtp_port'       => 'nullable|integer|min:1|max:65535',
            'smtp_encryption' => 'nullable|in:tls,ssl,starttls,',
            'smtp_username'   => 'nullable|string|max:255',
            'smtp_password'   => 'nullable|string|max:255',
            'smtp_from_email' => 'nullable|email|max:255',
            'smtp_from_name'  => 'nullable|string|max:100',
        ]);

        $fields = ['host', 'port', 'encryption', 'username', 'from_email', 'from_name'];
        foreach ($fields as $field) {
            Configuration::set("smtp_{$field}", $request->input("smtp_{$field}") ?? '');
        }

        // Only update password if a new one was entered
        if ($request->filled('smtp_password')) {
            Configuration::set('smtp_password', $request->input('smtp_password'));
        }

        return redirect()->route('admin.configuraciones.index')
            ->with('success', 'Configuración SMTP guardada correctamente.');
    }
}
