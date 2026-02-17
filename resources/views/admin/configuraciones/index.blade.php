@extends('layouts.admin')

@section('title', 'Configuraciones')
@section('header', 'Configuraciones')

@section('content')
<div class="max-w-3xl space-y-8">

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

</div>
@endsection
