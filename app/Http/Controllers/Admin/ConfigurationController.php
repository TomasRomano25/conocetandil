<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Services\MercadoPagoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

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

        $recaptcha = [
            'site_key'   => Configuration::get('recaptcha_site_key', ''),
            'secret_key' => Configuration::get('recaptcha_secret_key', ''),
        ];

        $allDays    = range(1, 7);
        $allTypes   = ['nature' => '🌿 Naturaleza', 'gastronomy' => '🧀 Gastronomía', 'adventure' => '🧗 Aventura', 'relax' => '🛁 Relax', 'mixed' => '✨ Mixto'];
        $allSeasons = ['summer' => '☀️ Verano', 'winter' => '❄️ Invierno', 'all' => '🍃 No sé'];

        $itineraryFilters = [
            'days'    => json_decode(Configuration::get('itinerary_days_enabled',    json_encode($allDays)), true)    ?? $allDays,
            'types'   => json_decode(Configuration::get('itinerary_types_enabled',   json_encode(array_keys($allTypes))), true)   ?? array_keys($allTypes),
            'seasons' => json_decode(Configuration::get('itinerary_seasons_enabled', json_encode(array_keys($allSeasons))), true) ?? array_keys($allSeasons),
            'all_days'    => $allDays,
            'all_types'   => $allTypes,
            'all_seasons' => $allSeasons,
        ];

        $paymentMethods = [
            'bank_transfer_enabled'  => Configuration::get('payment_bank_transfer_enabled', '1'),
            'mercadopago_enabled'    => Configuration::get('payment_mercadopago_enabled', '0'),
            'mp_sandbox'             => Configuration::get('mp_sandbox', '1'),
            'mp_public_key'          => Configuration::get('mp_public_key', ''),
            'mp_access_token_set'    => !empty(Configuration::get('mp_access_token', '')),
        ];

        $currentIp = request()->ip();

        $maintenanceConfig = [
            'enabled'   => Configuration::get('maintenance_enabled', '0'),
            'whitelist' => Configuration::get('maintenance_whitelist', ''),
        ];

        $plannerHero = Configuration::get('planner_hero_image');

        return view('admin.configuraciones.index', compact('config', 'latestFile', 'latestSize', 'backupCount', 'smtp', 'payment', 'recaptcha', 'itineraryFilters', 'paymentMethods', 'currentIp', 'maintenanceConfig', 'plannerHero'));
    }

    public function updateMaintenance(Request $request)
    {
        Configuration::set('maintenance_enabled', $request->boolean('maintenance_enabled') ? '1' : '0');
        Configuration::set('maintenance_whitelist', $request->input('maintenance_whitelist', ''));
        return redirect()->route('admin.configuraciones.index')->with('success', 'Configuración de mantenimiento guardada.');
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

    public function updateRecaptcha(Request $request)
    {
        $request->validate([
            'recaptcha_site_key'   => 'nullable|string|max:255',
            'recaptcha_secret_key' => 'nullable|string|max:255',
        ]);

        Configuration::set('recaptcha_site_key', $request->input('recaptcha_site_key') ?? '');
        if ($request->filled('recaptcha_secret_key')) {
            Configuration::set('recaptcha_secret_key', $request->input('recaptcha_secret_key'));
        }

        return redirect()->route('admin.configuraciones.index')
            ->with('success', 'Configuración de reCAPTCHA guardada.');
    }

    public function updateItineraryFilters(Request $request)
    {
        $days    = array_map('intval', $request->input('days', []));
        $types   = $request->input('types', []);
        $seasons = $request->input('seasons', []);

        $validDays    = array_values(array_filter($days, fn($d) => $d >= 1 && $d <= 7));
        $validTypes   = array_values(array_intersect($types, ['nature', 'gastronomy', 'adventure', 'relax', 'mixed']));
        $validSeasons = array_values(array_intersect($seasons, ['summer', 'winter', 'all']));

        Configuration::set('itinerary_days_enabled',    json_encode($validDays));
        Configuration::set('itinerary_types_enabled',   json_encode($validTypes));
        Configuration::set('itinerary_seasons_enabled', json_encode($validSeasons));

        return redirect()->route('admin.configuraciones.index')
            ->with('success', 'Filtros de itinerarios guardados correctamente.');
    }

    public function updatePlannerHero(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $existing = Configuration::get('planner_hero_image');
        if ($existing) {
            Storage::disk('public')->delete($existing);
        }

        $path = $request->file('image')->store('planner', 'public');
        Configuration::set('planner_hero_image', $path);

        return redirect()->route('admin.configuraciones.index')
            ->with('success', 'Imagen del hero del Planificador actualizada.');
    }

    public function deletePlannerHero()
    {
        $existing = Configuration::get('planner_hero_image');
        if ($existing) {
            Storage::disk('public')->delete($existing);
            Configuration::set('planner_hero_image', null);
        }

        return redirect()->route('admin.configuraciones.index')
            ->with('success', 'Imagen del hero eliminada.');
    }

    public function updatePaymentMethods(Request $request)
    {
        $request->validate([
            'mp_public_key'   => 'nullable|string|max:255',
            'mp_access_token' => 'nullable|string|max:255',
        ]);

        Configuration::set('payment_bank_transfer_enabled', $request->boolean('payment_bank_transfer_enabled') ? '1' : '0');
        Configuration::set('payment_mercadopago_enabled',   $request->boolean('payment_mercadopago_enabled') ? '1' : '0');
        Configuration::set('mp_sandbox',                    $request->boolean('mp_sandbox') ? '1' : '0');
        Configuration::set('mp_public_key',                 $request->input('mp_public_key') ?? '');

        if ($request->filled('mp_access_token')) {
            Configuration::set('mp_access_token', $request->input('mp_access_token'));
        }

        return redirect()->route('admin.configuraciones.index')
            ->with('success', 'Métodos de pago guardados correctamente.');
    }

    public function testMercadoPago()
    {
        $mp = new MercadoPagoService();

        if (! $mp->isConfigured()) {
            return response()->json(['success' => false, 'message' => 'Access Token no configurado.']);
        }

        try {
            // We test by hitting the /users/me endpoint with the token
            $response = \Illuminate\Support\Facades\Http::withToken($mp->getAccessToken())
                ->get('https://api.mercadopago.com/users/me');

            if ($response->successful()) {
                $data = $response->json();
                $email = $data['email'] ?? 'desconocido';
                $mode  = $mp->isSandbox() ? 'sandbox' : 'producción';
                return response()->json([
                    'success' => true,
                    'message' => "Conexión exitosa. Cuenta: {$email} (modo {$mode}).",
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas. Código: ' . $response->status(),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function testSmtp()
    {
        $host      = Configuration::get('smtp_host');
        $port      = Configuration::get('smtp_port', 587);
        $encryption = Configuration::get('smtp_encryption', 'tls') ?: null;
        $username  = Configuration::get('smtp_username');
        $password  = Configuration::get('smtp_password');
        $fromEmail = Configuration::get('smtp_from_email');
        $fromName  = Configuration::get('smtp_from_name', 'Conoce Tandil');

        if (! $host || ! $fromEmail) {
            return response()->json(['success' => false, 'message' => 'Configurá primero el host SMTP y el email remitente.']);
        }

        // Always apply config explicitly — don't rely on AppServiceProvider cache
        config([
            'mail.default'                 => 'smtp',
            'mail.mailers.smtp.host'       => $host,
            'mail.mailers.smtp.port'       => $port,
            'mail.mailers.smtp.encryption' => $encryption,
            'mail.mailers.smtp.username'   => $username,
            'mail.mailers.smtp.password'   => $password,
            'mail.from.address'            => $fromEmail,
            'mail.from.name'               => $fromName,
        ]);

        // Force Laravel to create a fresh mailer instance with the new config
        app('mail.manager')->forgetMailers();

        try {
            \Illuminate\Support\Facades\Mail::mailer('smtp')->raw(
                'Este es un email de prueba enviado desde el panel de administración de Conoce Tandil. Si lo ves, ¡la configuración SMTP funciona correctamente!',
                function ($message) use ($fromEmail) {
                    $message->to($fromEmail)->subject('Prueba de Email — Conoce Tandil');
                }
            );

            return response()->json([
                'success' => true,
                'message' => "Email enviado a {$fromEmail} vía {$host}:{$port}. Revisá tu bandeja de entrada (y spam).",
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => "Error enviando vía {$host}:{$port} — " . $e->getMessage(),
            ]);
        }
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
