<?php

namespace App\Providers;

use App\Models\Configuration;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Apply SMTP settings from DB on every request, overriding .env defaults
        try {
            $host = Configuration::get('smtp_host');
            if ($host) {
                config([
                    'mail.default'                 => 'smtp',
                    'mail.mailers.smtp.host'       => $host,
                    'mail.mailers.smtp.port'       => Configuration::get('smtp_port', 587),
                    'mail.mailers.smtp.encryption' => Configuration::get('smtp_encryption', 'tls') ?: null,
                    'mail.mailers.smtp.username'   => Configuration::get('smtp_username'),
                    'mail.mailers.smtp.password'   => Configuration::get('smtp_password'),
                    'mail.from.address'            => Configuration::get('smtp_from_email', config('mail.from.address')),
                    'mail.from.name'               => Configuration::get('smtp_from_name', config('mail.from.name')),
                ]);
            }
        } catch (\Throwable) {
            // DB not yet available (e.g. during migrations) — skip silently
        }
    }
}
