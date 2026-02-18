<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Services\GoogleAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        $analytics = [
            'ga4_id'      => Configuration::get('analytics_ga4_id', ''),
            'gtm_id'      => Configuration::get('analytics_gtm_id', ''),
            'property_id' => Configuration::get('analytics_ga4_property_id', ''),
            'enabled'     => Configuration::get('analytics_enabled', '0') === '1',
        ];

        $hasCredentials = file_exists(storage_path('app/analytics/service-account.json'));
        $credentialEmail = null;
        $metrics = null;
        $error   = null;

        if ($hasCredentials) {
            $sa = json_decode(file_get_contents(storage_path('app/analytics/service-account.json')), true);
            $credentialEmail = $sa['client_email'] ?? null;
        }

        if ($hasCredentials && $analytics['property_id']) {
            try {
                $propertyId = $analytics['property_id'];
                $metrics = Cache::remember("ga4_metrics_{$propertyId}", 3600, function () use ($propertyId) {
                    return (new GoogleAnalyticsService())->getMetrics($propertyId);
                });
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return view('admin.analytics.dashboard', compact('analytics', 'hasCredentials', 'credentialEmail', 'metrics', 'error'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'analytics_ga4_id'          => 'nullable|string|max:50',
            'analytics_gtm_id'          => 'nullable|string|max:50',
            'analytics_ga4_property_id' => 'nullable|string|max:20',
            'service_account_json'      => 'nullable|file|mimes:json',
        ]);

        Configuration::set('analytics_ga4_id',          $request->input('analytics_ga4_id', ''));
        Configuration::set('analytics_gtm_id',          $request->input('analytics_gtm_id', ''));
        Configuration::set('analytics_ga4_property_id', $request->input('analytics_ga4_property_id', ''));
        Configuration::set('analytics_enabled',         $request->boolean('analytics_enabled') ? '1' : '0');

        if ($request->hasFile('service_account_json')) {
            $content = file_get_contents($request->file('service_account_json')->getRealPath());
            $decoded = json_decode($content, true);

            if (!$decoded || empty($decoded['client_email']) || empty($decoded['private_key'])) {
                return back()->with('error', 'El archivo JSON no es una cuenta de servicio válida de Google.');
            }

            $dir = storage_path('app/analytics');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            file_put_contents($dir . '/service-account.json', $content);
            Cache::forget('ga4_access_token');
            Cache::forget('ga4_metrics_' . $request->input('analytics_ga4_property_id', ''));
        }

        return redirect()->route('admin.analytics.dashboard')
            ->with('success', 'Configuración de Analytics guardada correctamente.');
    }

    public function refresh()
    {
        $propertyId = Configuration::get('analytics_ga4_property_id', '');
        Cache::forget('ga4_metrics_' . $propertyId);
        Cache::forget('ga4_access_token');

        return redirect()->route('admin.analytics.dashboard')
            ->with('success', 'Datos actualizados correctamente.');
    }

    public function deleteCredentials()
    {
        $path = storage_path('app/analytics/service-account.json');
        if (file_exists($path)) {
            unlink($path);
        }

        $propertyId = Configuration::get('analytics_ga4_property_id', '');
        Cache::forget('ga4_access_token');
        Cache::forget('ga4_metrics_' . $propertyId);

        return redirect()->route('admin.analytics.dashboard')
            ->with('success', 'Credenciales eliminadas correctamente.');
    }
}
