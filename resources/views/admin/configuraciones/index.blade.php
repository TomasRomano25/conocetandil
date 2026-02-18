@extends('layouts.admin')
@php use App\Models\Configuration; @endphp

@section('title', 'Configuraciones')
@section('header', 'Configuraciones')

@section('content')
<div class="max-w-3xl space-y-8">

    {{-- ══════════════════════════════════════════════════════════
         SMTP — Email configuration card
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-[#1A1A1A]">Configuración de Email (SMTP)</h2>
                <p class="text-xs text-gray-500">Configura el servidor de correo para enviar notificaciones de mensajes.</p>
            </div>
        </div>

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

    {{-- ══════════════════════════════════════════════════════════
         COPIAS DE SEGURIDAD — Settings card
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Card header --}}
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-[#1A1A1A]">Copias de Seguridad</h2>
                <p class="text-xs text-gray-500">Configura el respaldo automático de la base de datos.</p>
            </div>
        </div>

        {{-- Settings form --}}
        <form method="POST" action="{{ route('admin.configuraciones.backup.update') }}" class="px-6 py-5 space-y-5">
            @csrf

            {{-- Enable toggle --}}
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

            {{-- Interval --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Intervalo entre backups
                    </label>
                    <select name="backup_interval_hours"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                        @foreach ([1 => 'Cada 1 hora', 2 => 'Cada 2 horas', 4 => 'Cada 4 horas', 6 => 'Cada 6 horas', 12 => 'Cada 12 horas', 24 => 'Una vez al día'] as $val => $label)
                            <option value="{{ $val }}" @selected((int)$config['backup_interval_hours'] === $val)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Backups a conservar
                    </label>
                    <select name="backup_keep_count"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                        @foreach ([5 => 'Últimos 5', 10 => 'Últimos 10', 20 => 'Últimos 20', 30 => 'Últimos 30'] as $val => $label)
                            <option value="{{ $val }}" @selected((int)$config['backup_keep_count'] === $val)>
                                {{ $label }}
                            </option>
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
    </div>

    {{-- ══════════════════════════════════════════════════════════
         ESTADO DEL BACKUP — Info + actions card
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-[#1A1A1A]">Estado del último backup</h2>
                <p class="text-xs text-gray-500">Información del respaldo más reciente disponible.</p>
            </div>
        </div>

        <div class="px-6 py-5 space-y-4">

            {{-- Stats row --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-[#2D6A4F]">{{ $backupCount }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Backups guardados</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <p class="text-sm font-bold text-[#1A1A1A] leading-tight">
                        @if ($config['backup_last_run'])
                            {{ \Carbon\Carbon::parse($config['backup_last_run'])->format('d/m/Y') }}
                        @else
                            —
                        @endif
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5">Fecha último backup</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <p class="text-sm font-bold text-[#1A1A1A] leading-tight">
                        @if ($latestSize)
                            {{ $latestSize }} KB
                        @else
                            —
                        @endif
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5">Tamaño del archivo</p>
                </div>
            </div>

            {{-- Last run detail --}}
            <div class="flex items-center gap-2 text-sm {{ $config['backup_last_run'] ? 'text-gray-600' : 'text-gray-400' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                @if ($config['backup_last_run'])
                    Último backup:
                    <span class="font-semibold text-[#1A1A1A]">
                        {{ \Carbon\Carbon::parse($config['backup_last_run'])->format('d/m/Y H:i:s') }}
                    </span>
                    <span class="text-gray-400">({{ \Carbon\Carbon::parse($config['backup_last_run'])->diffForHumans() }})</span>
                @else
                    Ningún backup realizado todavía.
                @endif
            </div>

            {{-- Action buttons --}}
            <div class="flex flex-wrap gap-3 pt-1">
                {{-- Manual backup --}}
                <form method="POST" action="{{ route('admin.configuraciones.backup.run') }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2.5 px-5 rounded-lg transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Generar backup ahora
                    </button>
                </form>

                {{-- Download --}}
                @if ($latestFile)
                    <a href="{{ route('admin.configuraciones.backup.download') }}"
                        class="inline-flex items-center gap-2 border-2 border-[#2D6A4F] text-[#2D6A4F] hover:bg-[#2D6A4F] hover:text-white font-semibold py-2.5 px-5 rounded-lg transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Descargar último backup
                    </a>
                @else
                    <button disabled
                        class="inline-flex items-center gap-2 border-2 border-gray-200 text-gray-400 font-semibold py-2.5 px-5 rounded-lg text-sm cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Sin backups disponibles
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         CRON — Setup instructions
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-6 py-5">
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-bold text-amber-800 mb-1">Configuración del servidor requerida</p>
                <p class="text-sm text-amber-700 mb-3">
                    Para que los backups automáticos funcionen, agregá esta línea al cron de tu servidor
                    (ejecutá <code class="bg-amber-100 px-1 rounded text-xs">crontab -e</code>):
                </p>
                <div class="bg-amber-100 rounded-lg px-4 py-2.5 font-mono text-xs text-amber-900 select-all break-all">
                    * * * * * cd {{ base_path() }} &amp;&amp; php artisan schedule:run >> /dev/null 2>&amp;1
                </div>
                <p class="text-xs text-amber-600 mt-2">
                    El scheduler verifica cada minuto si es momento de generar un backup según el intervalo configurado.
                </p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         URL DEL PANEL ADMIN
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-[#1A1A1A]">URL del panel de administración</h2>
                <p class="text-xs text-gray-500">Prefijo actual de la URL del admin.</p>
            </div>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600">URL actual:</span>
                <code class="bg-gray-100 text-[#1A1A1A] font-mono text-sm px-3 py-1.5 rounded-lg">
                    {{ url(env('ADMIN_PREFIX', 'admin')) }}
                </code>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 text-sm text-blue-800">
                <p class="font-semibold mb-1">¿Cómo cambiar la URL?</p>
                <p class="mb-2">Editá la variable <code class="bg-blue-100 px-1 rounded text-xs">ADMIN_PREFIX</code> en el archivo <code class="bg-blue-100 px-1 rounded text-xs">.env</code> del servidor:</p>
                <div class="bg-blue-100 rounded px-3 py-2 font-mono text-xs text-blue-900 select-all">
                    ADMIN_PREFIX=mi_prefijo_secreto
                </div>
                <p class="mt-2 text-xs text-blue-600">Después ejecutá <code class="bg-blue-100 px-1 rounded">php artisan route:clear && php artisan route:cache</code> para aplicar el cambio.</p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         MÉTODOS DE PAGO — Transferencia Bancaria
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-[#1A1A1A] text-sm">Métodos de Pago</h2>
                <p class="text-xs text-gray-500">Transferencia bancaria — estos datos se muestran en el checkout.</p>
            </div>
        </div>

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
                        placeholder="22 dígitos"
                        maxlength="22"
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
                <p class="text-xs text-gray-400">
                    <a href="{{ route('membership.planes') }}" target="_blank" class="text-[#2D6A4F] hover:underline">
                        Ver página de planes →
                    </a>
                </p>
                <button type="submit"
                    class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-6 rounded-lg transition text-sm">
                    Guardar datos bancarios
                </button>
            </div>
        </form>
    </div>


    {{-- ══════════════════════════════════════════════════════════
         ANALYTICS — Google Analytics 4 & GTM
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h2 class="text-base font-bold text-[#1A1A1A]">Analytics</h2>
                <p class="text-xs text-gray-500">Google Analytics 4 y Google Tag Manager</p>
            </div>
            <a href="{{ route('admin.analytics.dashboard') }}"
                class="inline-flex items-center gap-1.5 text-sm text-[#2D6A4F] hover:underline font-medium">
                Ver dashboard
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="px-6 py-5 space-y-4">
            {{-- Status indicators --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">Seguimiento</p>
                    @php $analyticsOn = Configuration::get('analytics_enabled', '0') === '1'; @endphp
                    <p class="text-sm font-bold {{ $analyticsOn ? 'text-green-700' : 'text-gray-400' }}">
                        {{ $analyticsOn ? 'Activo' : 'Inactivo' }}
                    </p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">GTM Container</p>
                    @php $gtmId = Configuration::get('analytics_gtm_id', ''); @endphp
                    <p class="text-sm font-mono font-bold {{ $gtmId ? 'text-[#2D6A4F]' : 'text-gray-400' }}">
                        {{ $gtmId ?: 'Sin configurar' }}
                    </p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">GA4 Measurement ID</p>
                    @php $ga4Id = Configuration::get('analytics_ga4_id', ''); @endphp
                    <p class="text-sm font-mono font-bold {{ $ga4Id ? 'text-[#2D6A4F]' : 'text-gray-400' }}">
                        {{ $ga4Id ?: 'Sin configurar' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-between pt-1">
                <p class="text-xs text-gray-400">
                    Configurá los IDs y credenciales en la sección de Analytics para activar el seguimiento y ver las métricas en el dashboard.
                </p>
                <a href="{{ route('admin.analytics.dashboard') }}"
                    class="flex-shrink-0 ml-4 bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-5 rounded-lg transition text-sm">
                    Ir a Analytics
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
