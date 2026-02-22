@extends('layouts.admin')
@php use App\Models\Configuration; @endphp

@section('title', 'Configuraciones')
@section('header', 'Configuraciones')

@section('content')
<div class="max-w-3xl space-y-3">

    {{-- ══════════════════════════════════════════════════════════
         MANTENIMIENTO
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <button type="button" onclick="toggleSection('mantenimiento')"
            class="w-full px-6 py-4 flex items-center gap-3 text-left hover:bg-gray-50 transition group">
            <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-[#1A1A1A]">Modo Mantenimiento</p>
                <p class="text-xs text-gray-500 mt-0.5">Coming soon page para visitantes</p>
            </div>
            @if ($maintenanceConfig['enabled'] === '1')
                <span class="text-xs font-medium bg-red-100 text-red-700 px-2 py-1 rounded-full flex-shrink-0">Activo</span>
            @else
                <span class="text-xs font-medium bg-gray-100 text-gray-500 px-2 py-1 rounded-full flex-shrink-0">Inactivo</span>
            @endif
            <svg id="chevron-mantenimiento" class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div id="section-mantenimiento" class="hidden border-t border-gray-100">
            <form method="POST" action="{{ route('admin.configuraciones.mantenimiento.update') }}" class="px-6 py-5 space-y-5">
                @csrf

                {{-- Toggle --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Activar modo mantenimiento</p>
                        <p class="text-xs text-gray-500 mt-0.5">Los visitantes verán la página "Volvemos pronto"</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="maintenance_enabled" value="1"
                            class="sr-only peer"
                            {{ $maintenanceConfig['enabled'] === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#52B788] rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2D6A4F]"></div>
                    </label>
                </div>

                {{-- IP Whitelist --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-semibold text-gray-700">IPs permitidas (whitelist)</label>
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <span>Tu IP: <code class="bg-gray-100 px-1.5 py-0.5 rounded font-mono text-[#2D6A4F]">{{ $currentIp }}</code></span>
                            <button type="button"
                                onclick="addMyIp('{{ $currentIp }}')"
                                class="text-xs font-medium text-[#2D6A4F] hover:text-[#52B788] underline underline-offset-2 transition">
                                Agregar mi IP
                            </button>
                        </div>
                    </div>
                    <textarea name="maintenance_whitelist" id="maintenance-whitelist" rows="4"
                        placeholder="Una IP por línea&#10;192.168.1.1&#10;10.0.0.1"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#52B788] resize-none">{{ old('maintenance_whitelist', $maintenanceConfig['whitelist']) }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Las IPs en esta lista podrán acceder al sitio aunque el mantenimiento esté activo.</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-[#2D6A4F] hover:bg-[#1e4d38] text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                        Guardar configuración
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         SMTP
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <button type="button" onclick="toggleSection('smtp')"
            class="w-full px-6 py-4 flex items-center gap-3 text-left hover:bg-gray-50 transition group">
            <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-[#1A1A1A]">Configuración de Email (SMTP)</p>
                <p class="text-xs text-gray-500 mt-0.5">Servidor de correo para notificaciones</p>
            </div>
            @if (Configuration::get('smtp_host'))
                <span class="text-xs font-medium bg-green-100 text-green-700 px-2 py-1 rounded-full flex-shrink-0">Configurado</span>
            @else
                <span class="text-xs font-medium bg-gray-100 text-gray-500 px-2 py-1 rounded-full flex-shrink-0">Sin configurar</span>
            @endif
            <svg id="chevron-smtp" class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div id="section-smtp" class="hidden border-t border-gray-100">
            <form method="POST" action="{{ route('admin.configuraciones.smtp.update') }}" class="px-6 py-5 space-y-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Servidor SMTP (host)</label>
                        <input type="text" name="smtp_host" value="{{ old('smtp_host', $smtp['host']) }}"
                            placeholder="smtp.gmail.com"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Puerto</label>
                        <input type="number" name="smtp_port" value="{{ old('smtp_port', $smtp['port']) }}"
                            placeholder="587"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Encriptación</label>
                        <select name="smtp_encryption"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                            <option value="tls"      @selected($smtp['encryption'] === 'tls')>TLS (recomendado)</option>
                            <option value="ssl"      @selected($smtp['encryption'] === 'ssl')>SSL</option>
                            <option value="starttls" @selected($smtp['encryption'] === 'starttls')>STARTTLS</option>
                            <option value=""         @selected($smtp['encryption'] === '')>Sin encriptación</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Usuario</label>
                        <input type="text" name="smtp_username" value="{{ old('smtp_username', $smtp['username']) }}"
                            placeholder="tu@email.com"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Contraseña</label>
                        <input type="password" name="smtp_password" placeholder="Dejar vacío para no cambiar"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                        @if (Configuration::get('smtp_password'))
                            <p class="text-xs text-green-600 mt-1">✓ Contraseña guardada</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email remitente</label>
                        <input type="email" name="smtp_from_email" value="{{ old('smtp_from_email', $smtp['from_email']) }}"
                            placeholder="noreply@conocetandil.com"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nombre remitente</label>
                        <input type="text" name="smtp_from_name" value="{{ old('smtp_from_name', $smtp['from_name']) }}"
                            placeholder="Conoce Tandil"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                        Guardar configuración SMTP
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         COPIAS DE SEGURIDAD
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <button type="button" onclick="toggleSection('backup')"
            class="w-full px-6 py-4 flex items-center gap-3 text-left hover:bg-gray-50 transition">
            <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-[#1A1A1A]">Copias de Seguridad</p>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $backupCount }} backup(s) guardados
                    @if ($config['backup_last_run'])
                        · Último: {{ \Carbon\Carbon::parse($config['backup_last_run'])->diffForHumans() }}
                    @endif
                </p>
            </div>
            @if ($config['backup_enabled'] === '1')
                <span class="text-xs font-medium bg-green-100 text-green-700 px-2 py-1 rounded-full flex-shrink-0">Activo</span>
            @else
                <span class="text-xs font-medium bg-gray-100 text-gray-500 px-2 py-1 rounded-full flex-shrink-0">Inactivo</span>
            @endif
            <svg id="chevron-backup" class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div id="section-backup" class="hidden border-t border-gray-100 divide-y divide-gray-50">
            {{-- Settings form --}}
            <form method="POST" action="{{ route('admin.configuraciones.backup.update') }}" class="px-6 py-5 space-y-5">
                @csrf
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Backups automáticos</p>
                        <p class="text-xs text-gray-500 mt-0.5">Activa o desactiva el sistema de respaldo programado.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="backup_enabled" value="1" class="sr-only peer"
                            {{ $config['backup_enabled'] === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer
                                    peer-checked:after:translate-x-full peer-checked:after:border-white
                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                    after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                                    peer-checked:bg-[#2D6A4F]"></div>
                    </label>
                </div>
                <hr class="border-gray-100">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Intervalo entre backups</label>
                        <select name="backup_interval_hours"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                            @foreach ([1 => 'Cada 1 hora', 2 => 'Cada 2 horas', 4 => 'Cada 4 horas', 6 => 'Cada 6 horas', 12 => 'Cada 12 horas', 24 => 'Una vez al día'] as $val => $label)
                                <option value="{{ $val }}" @selected((int)$config['backup_interval_hours'] === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Backups a conservar</label>
                        <select name="backup_keep_count"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                            @foreach ([5 => 'Últimos 5', 10 => 'Últimos 10', 20 => 'Últimos 20', 30 => 'Últimos 30'] as $val => $label)
                                <option value="{{ $val }}" @selected((int)$config['backup_keep_count'] === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                        Guardar configuración
                    </button>
                </div>
            </form>

            {{-- Status + actions --}}
            <div class="px-6 py-5 space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-[#2D6A4F]">{{ $backupCount }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Backups guardados</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-sm font-bold text-[#1A1A1A] leading-tight">
                            {{ $config['backup_last_run'] ? \Carbon\Carbon::parse($config['backup_last_run'])->format('d/m/Y') : '—' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">Fecha último backup</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-sm font-bold text-[#1A1A1A] leading-tight">{{ $latestSize ? $latestSize . ' KB' : '—' }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Tamaño del archivo</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('admin.configuraciones.backup.run') }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2.5 px-5 rounded-lg transition text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Generar backup ahora
                        </button>
                    </form>
                    @if ($latestFile)
                        <a href="{{ route('admin.configuraciones.backup.download') }}"
                            class="inline-flex items-center gap-2 border-2 border-[#2D6A4F] text-[#2D6A4F] hover:bg-[#2D6A4F] hover:text-white font-semibold py-2.5 px-5 rounded-lg transition text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Descargar último backup
                        </a>
                    @endif
                </div>
            </div>

            {{-- Cron note --}}
            <div class="px-6 py-4 bg-amber-50">
                <div class="flex gap-3">
                    <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="text-xs font-bold text-amber-800 mb-1">Cron del servidor requerido</p>
                        <div class="bg-amber-100 rounded px-3 py-2 font-mono text-xs text-amber-900 select-all break-all">
                            * * * * * cd {{ base_path() }} &amp;&amp; php artisan schedule:run >> /dev/null 2>&amp;1
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         MÉTODOS DE PAGO
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <button type="button" onclick="toggleSection('payment')"
            class="w-full px-6 py-4 flex items-center gap-3 text-left hover:bg-gray-50 transition">
            <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-[#1A1A1A]">Métodos de Pago</p>
                <p class="text-xs text-gray-500 mt-0.5">Transferencia bancaria — datos del checkout</p>
            </div>
            @if ($payment['bank_cbu'] || $payment['bank_alias'])
                <span class="text-xs font-medium bg-green-100 text-green-700 px-2 py-1 rounded-full flex-shrink-0">Configurado</span>
            @else
                <span class="text-xs font-medium bg-gray-100 text-gray-500 px-2 py-1 rounded-full flex-shrink-0">Sin configurar</span>
            @endif
            <svg id="chevron-payment" class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div id="section-payment" class="hidden border-t border-gray-100">
            <form method="POST" action="{{ route('admin.configuraciones.payment.update') }}" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del banco</label>
                        <input type="text" name="bank_name" value="{{ $payment['bank_name'] }}"
                            placeholder="Ej: Banco Provincia"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titular de la cuenta</label>
                        <input type="text" name="bank_account_holder" value="{{ $payment['bank_account_holder'] }}"
                            placeholder="Nombre completo o razón social"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CBU</label>
                        <input type="text" name="bank_cbu" value="{{ $payment['bank_cbu'] }}"
                            placeholder="22 dígitos" maxlength="22"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 font-mono focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alias</label>
                        <input type="text" name="bank_alias" value="{{ $payment['bank_alias'] }}"
                            placeholder="Ej: MI.ALIAS.TANDIL"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Número de cuenta <span class="text-gray-400 font-normal">(opcional)</span></label>
                        <input type="text" name="bank_account_number" value="{{ $payment['bank_account_number'] }}"
                            placeholder="Ej: 000-000000/0"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Instrucciones adicionales <span class="text-gray-400 font-normal">(opcional)</span></label>
                        <textarea name="bank_instructions" rows="3"
                            placeholder="Ej: Por favor incluí tu nombre y el plan en el concepto de la transferencia."
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">{{ $payment['bank_instructions'] }}</textarea>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('membership.planes') }}" target="_blank" class="text-xs text-[#2D6A4F] hover:underline">Ver página de planes →</a>
                    <button type="submit"
                        class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-6 rounded-lg transition text-sm">
                        Guardar datos bancarios
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         URL DEL PANEL ADMIN
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <button type="button" onclick="toggleSection('adminurl')"
            class="w-full px-6 py-4 flex items-center gap-3 text-left hover:bg-gray-50 transition">
            <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-[#1A1A1A]">URL del panel de administración</p>
                <p class="text-xs text-gray-500 mt-0.5 font-mono">{{ url(env('ADMIN_PREFIX', 'admin')) }}</p>
            </div>
            <svg id="chevron-adminurl" class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div id="section-adminurl" class="hidden border-t border-gray-100">
            <div class="px-6 py-5 space-y-4">
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-600">URL actual:</span>
                    <code class="bg-gray-100 text-[#1A1A1A] font-mono text-sm px-3 py-1.5 rounded-lg">{{ url(env('ADMIN_PREFIX', 'admin')) }}</code>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 text-sm text-blue-800">
                    <p class="font-semibold mb-1">¿Cómo cambiar la URL?</p>
                    <p class="mb-2 text-xs">Editá <code class="bg-blue-100 px-1 rounded">ADMIN_PREFIX</code> en el archivo <code class="bg-blue-100 px-1 rounded">.env</code> del servidor:</p>
                    <div class="bg-blue-100 rounded px-3 py-2 font-mono text-xs text-blue-900 select-all">ADMIN_PREFIX=mi_prefijo_secreto</div>
                    <p class="mt-2 text-xs text-blue-600">Después ejecutá <code class="bg-blue-100 px-1 rounded">php artisan route:clear && php artisan route:cache</code></p>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         ANALYTICS
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <button type="button" onclick="toggleSection('analytics')"
            class="w-full px-6 py-4 flex items-center gap-3 text-left hover:bg-gray-50 transition">
            <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-[#1A1A1A]">Analytics</p>
                <p class="text-xs text-gray-500 mt-0.5">Google Analytics 4 y Google Tag Manager</p>
            </div>
            @php $analyticsOn = Configuration::get('analytics_enabled', '0') === '1'; @endphp
            @if ($analyticsOn)
                <span class="text-xs font-medium bg-green-100 text-green-700 px-2 py-1 rounded-full flex-shrink-0">Activo</span>
            @else
                <span class="text-xs font-medium bg-gray-100 text-gray-500 px-2 py-1 rounded-full flex-shrink-0">Inactivo</span>
            @endif
            <svg id="chevron-analytics" class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div id="section-analytics" class="hidden border-t border-gray-100">
            <div class="px-6 py-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500 mb-1">Seguimiento</p>
                        <p class="text-sm font-bold {{ $analyticsOn ? 'text-green-700' : 'text-gray-400' }}">{{ $analyticsOn ? 'Activo' : 'Inactivo' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500 mb-1">GTM Container</p>
                        @php $gtmId = Configuration::get('analytics_gtm_id', ''); @endphp
                        <p class="text-sm font-mono font-bold {{ $gtmId ? 'text-[#2D6A4F]' : 'text-gray-400' }}">{{ $gtmId ?: 'Sin configurar' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500 mb-1">GA4 Measurement ID</p>
                        @php $ga4Id = Configuration::get('analytics_ga4_id', ''); @endphp
                        <p class="text-sm font-mono font-bold {{ $ga4Id ? 'text-[#2D6A4F]' : 'text-gray-400' }}">{{ $ga4Id ?: 'Sin configurar' }}</p>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('admin.analytics.dashboard') }}"
                        class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-5 rounded-lg transition text-sm">
                        Ir al dashboard de Analytics →
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         reCAPTCHA
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <button type="button" onclick="toggleSection('recaptcha')"
            class="w-full px-6 py-4 flex items-center gap-3 text-left hover:bg-gray-50 transition group">
            <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-[#1A1A1A]">reCAPTCHA v3</p>
                <p class="text-xs text-gray-500 mt-0.5">Protección contra bots en formularios públicos</p>
            </div>
            @if (Configuration::get('recaptcha_site_key'))
                <span class="text-xs font-medium bg-green-100 text-green-700 px-2 py-1 rounded-full flex-shrink-0">Configurado</span>
            @else
                <span class="text-xs font-medium bg-gray-100 text-gray-500 px-2 py-1 rounded-full flex-shrink-0">Sin configurar</span>
            @endif
            <svg id="chevron-recaptcha" class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div id="section-recaptcha" class="hidden border-t border-gray-100">
            <form method="POST" action="{{ route('admin.configuraciones.recaptcha.update') }}" class="px-6 py-5 space-y-5">
                @csrf
                <p class="text-xs text-gray-500">Registrá tu sitio en Google reCAPTCHA v3 y pegá aquí las claves. Si no configurás las claves, los formularios funcionarán sin verificación.</p>
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Site Key (clave pública)</label>
                        <input type="text" name="recaptcha_site_key" value="{{ old('recaptcha_site_key', $recaptcha['site_key']) }}"
                            placeholder="6Lc..."
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Secret Key (clave privada)</label>
                        <input type="password" name="recaptcha_secret_key" placeholder="Dejar vacío para no cambiar"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                        @if (Configuration::get('recaptcha_secret_key'))
                            <p class="text-xs text-green-600 mt-1">✓ Secret key guardada</p>
                        @endif
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                        Guardar reCAPTCHA
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         MÉTODOS DE PAGO (HABILITADOS + MERCADOPAGO)
         ══════════════════════════════════════════════════════════ --}}
    @php
        $mpEnabled = $paymentMethods['mercadopago_enabled'] === '1';
        $btEnabled = $paymentMethods['bank_transfer_enabled'] === '1';
        $mpBadge   = $mpEnabled ? 'MP activo' : ($btEnabled ? 'Solo transferencia' : 'Sin métodos');
        $mpBadgeColor = $mpEnabled ? 'bg-blue-100 text-blue-700' : ($btEnabled ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500');
    @endphp
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <button type="button" onclick="toggleSection('payment-methods')"
            class="w-full px-6 py-4 flex items-center gap-3 text-left hover:bg-gray-50 transition group">
            <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-[#1A1A1A]">Métodos de Pago</p>
                <p class="text-xs text-gray-500 mt-0.5">Transferencia bancaria y MercadoPago</p>
            </div>
            <span class="text-xs font-medium {{ $mpBadgeColor }} px-2 py-1 rounded-full flex-shrink-0">{{ $mpBadge }}</span>
            <svg id="chevron-payment-methods" class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div id="section-payment-methods" class="hidden border-t border-gray-100">
            <form method="POST" action="{{ route('admin.configuraciones.payment-methods.update') }}" class="px-6 py-5 space-y-6">
                @csrf

                {{-- Métodos habilitados --}}
                <div>
                    <p class="text-sm font-bold text-gray-700 mb-3">Métodos habilitados</p>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2">
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Transferencia bancaria</p>
                                <p class="text-xs text-gray-500 mt-0.5">Permite pagar enviando comprobante manual</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="payment_bank_transfer_enabled" value="1" class="sr-only peer"
                                    {{ $paymentMethods['bank_transfer_enabled'] === '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer
                                            peer-checked:after:translate-x-full peer-checked:after:border-white
                                            after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                            after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                                            peer-checked:bg-[#2D6A4F]"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <div>
                                <p class="text-sm font-semibold text-gray-700">MercadoPago</p>
                                <p class="text-xs text-gray-500 mt-0.5">Pago online con tarjeta, débito o efectivo</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="payment_mercadopago_enabled" value="1" class="sr-only peer"
                                    id="mp-enabled-toggle"
                                    {{ $paymentMethods['mercadopago_enabled'] === '1' ? 'checked' : '' }}
                                    onchange="document.getElementById('mp-credentials-section').classList.toggle('hidden', !this.checked)">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer
                                            peer-checked:after:translate-x-full peer-checked:after:border-white
                                            after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                            after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                                            peer-checked:bg-[#2D6A4F]"></div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- MercadoPago credentials --}}
                <div id="mp-credentials-section" class="{{ $paymentMethods['mercadopago_enabled'] === '1' ? '' : 'hidden' }} border-t border-gray-100 pt-5 space-y-5">
                    <p class="text-sm font-bold text-gray-700">Credenciales de MercadoPago</p>

                    {{-- Sandbox toggle --}}
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Modo sandbox (pruebas)</p>
                            <p class="text-xs text-gray-500 mt-0.5">Activá esto mientras hacés pruebas. Desactivá en producción.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="mp_sandbox" value="1" class="sr-only peer"
                                {{ $paymentMethods['mp_sandbox'] === '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer
                                        peer-checked:after:translate-x-full peer-checked:after:border-white
                                        after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                        after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                                        peer-checked:bg-amber-500"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Public Key</label>
                            <input type="text" name="mp_public_key"
                                value="{{ old('mp_public_key', $paymentMethods['mp_public_key']) }}"
                                placeholder="APP_USR-..."
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Access Token</label>
                            <input type="password" name="mp_access_token"
                                placeholder="Dejar vacío para no cambiar"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                            @if ($paymentMethods['mp_access_token_set'])
                                <p class="text-xs text-green-600 mt-1">✓ Access Token guardado</p>
                            @endif
                        </div>
                    </div>

                    {{-- Test connection button --}}
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="testMpConnection()"
                            class="inline-flex items-center gap-2 border-2 border-[#2D6A4F] text-[#2D6A4F] hover:bg-[#2D6A4F] hover:text-white font-semibold py-2 px-4 rounded-lg transition text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Probar conexión
                        </button>
                        <span id="mp-test-result" class="text-sm hidden"></span>
                    </div>

                    {{-- Webhook URL --}}
                    @if ($paymentMethods['mp_access_token_set'])
                    <div class="bg-gray-50 rounded-lg px-4 py-3">
                        <p class="text-xs font-semibold text-gray-600 mb-1">URL de Webhook (configurar en MP)</p>
                        <div class="font-mono text-xs text-gray-700 break-all select-all bg-white border border-gray-200 rounded px-3 py-2">
                            {{ route('webhooks.mercadopago') }}
                        </div>
                    </div>
                    @endif

                    {{-- Documentation block --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-4 text-sm text-blue-800 space-y-2">
                        <p class="font-bold">¿Cómo obtener las credenciales?</p>
                        <ol class="list-decimal list-inside space-y-1 text-xs text-blue-700">
                            <li>Ingresá a <a href="https://www.mercadopago.com.ar/developers/panel" target="_blank" class="underline font-semibold">mercadopago.com.ar/developers/panel</a></li>
                            <li>Creá una aplicación o seleccioná una existente</li>
                            <li>En <strong>Credenciales de producción</strong> encontrás la <em>Public Key</em> y el <em>Access Token</em> reales</li>
                            <li>Para pruebas usá las <strong>Credenciales de prueba</strong> (sandbox) y activá el modo sandbox arriba</li>
                            <li>En la sección <strong>Webhooks</strong> del panel, configurá la URL que aparece arriba</li>
                        </ol>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                        class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                        Guardar métodos de pago
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         FILTROS DE ITINERARIOS
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <button type="button" onclick="toggleSection('itinerarios')"
            class="w-full px-6 py-4 flex items-center gap-3 text-left hover:bg-gray-50 transition group">
            <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-[#1A1A1A]">Filtros del Planificador de Itinerarios</p>
                <p class="text-xs text-gray-500 mt-0.5">Habilitá o deshabilitá opciones del cuestionario Premium</p>
            </div>
            <svg id="chevron-itinerarios" class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div id="section-itinerarios" class="hidden border-t border-gray-100">
            <form method="POST" action="{{ route('admin.configuraciones.itinerarios.update') }}" class="px-6 py-5 space-y-6">
                @csrf
                <p class="text-xs text-gray-500">Las opciones deshabilitadas no aparecerán en el cuestionario del Planificador Premium. Si deshabilita todas las opciones de una categoría, se mostrará igual con todas activas.</p>

                {{-- Days --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Días disponibles</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($itineraryFilters['all_days'] as $d)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="days[]" value="{{ $d }}"
                                {{ in_array($d, $itineraryFilters['days']) ? 'checked' : '' }}
                                class="sr-only peer">
                            <span class="flex items-center justify-center w-10 h-10 rounded-xl border-2 border-gray-200 text-sm font-bold text-gray-500 peer-checked:border-[#2D6A4F] peer-checked:bg-[#2D6A4F] peer-checked:text-white transition select-none">
                                {{ $d }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5">días</p>
                </div>

                {{-- Types --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipos de experiencia</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($itineraryFilters['all_types'] as $key => $label)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="types[]" value="{{ $key }}"
                                {{ in_array($key, $itineraryFilters['types']) ? 'checked' : '' }}
                                class="sr-only peer">
                            <span class="inline-flex items-center px-3 py-2 rounded-xl border-2 border-gray-200 text-sm font-medium text-gray-600 peer-checked:border-[#2D6A4F] peer-checked:bg-[#2D6A4F]/10 peer-checked:text-[#2D6A4F] transition select-none">
                                {{ $label }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Seasons --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Temporadas</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($itineraryFilters['all_seasons'] as $key => $label)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="seasons[]" value="{{ $key }}"
                                {{ in_array($key, $itineraryFilters['seasons']) ? 'checked' : '' }}
                                class="sr-only peer">
                            <span class="inline-flex items-center px-3 py-2 rounded-xl border-2 border-gray-200 text-sm font-medium text-gray-600 peer-checked:border-[#2D6A4F] peer-checked:bg-[#2D6A4F]/10 peer-checked:text-[#2D6A4F] transition select-none">
                                {{ $label }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end pt-1">
                    <button type="submit"
                        class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                        Guardar filtros
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function testMpConnection() {
        var resultEl = document.getElementById('mp-test-result');
        resultEl.className = 'text-sm text-gray-500';
        resultEl.textContent = 'Probando...';
        resultEl.classList.remove('hidden');

        fetch('{{ route('admin.configuraciones.payment-methods.test-mp') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                resultEl.className = 'text-sm text-green-600 font-semibold';
            } else {
                resultEl.className = 'text-sm text-red-600';
            }
            resultEl.textContent = data.message;
        })
        .catch(() => {
            resultEl.className = 'text-sm text-red-600';
            resultEl.textContent = 'Error de red al probar la conexión.';
        });
    }

    function addMyIp(ip) {
        const ta = document.getElementById('maintenance-whitelist');
        const current = ta.value.trim();
        const lines = current ? current.split('\n').map(l => l.trim()).filter(Boolean) : [];
        if (!lines.includes(ip)) {
            lines.push(ip);
            ta.value = lines.join('\n');
        }
    }

    function toggleSection(id) {
        const section = document.getElementById('section-' + id);
        const chevron = document.getElementById('chevron-' + id);
        const isOpen  = !section.classList.contains('hidden');

        section.classList.toggle('hidden', isOpen);
        chevron.style.transform = isOpen ? '' : 'rotate(180deg)';

        // Persist state
        const open = JSON.parse(localStorage.getItem('cfg_open') || '{}');
        open[id] = !isOpen;
        localStorage.setItem('cfg_open', JSON.stringify(open));
    }

    // Restore state on load
    document.addEventListener('DOMContentLoaded', function () {
        const open = JSON.parse(localStorage.getItem('cfg_open') || '{}');
        Object.keys(open).forEach(function (id) {
            if (open[id]) {
                const section = document.getElementById('section-' + id);
                const chevron = document.getElementById('chevron-' + id);
                if (section) {
                    section.classList.remove('hidden');
                    chevron.style.transform = 'rotate(180deg)';
                }
            }
        });
    });
</script>
@endsection
