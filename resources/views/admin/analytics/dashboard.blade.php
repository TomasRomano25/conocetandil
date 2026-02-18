@extends('layouts.admin')

@section('title', 'Analytics')
@section('header', 'Analytics')

@section('content')
<div class="space-y-6">

    {{-- ═══════════════════════════════════════════
         SETTINGS CARD
         ═══════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-[#1A1A1A]">Configuración de Analytics</h2>
                    <p class="text-xs text-gray-500">Google Analytics 4 y Google Tag Manager</p>
                </div>
            </div>
            <button onclick="document.getElementById('settings-panel').classList.toggle('hidden')"
                class="text-sm text-[#2D6A4F] hover:underline font-medium">
                {{ $analytics['ga4_id'] || $analytics['gtm_id'] ? 'Editar configuración' : 'Configurar' }}
            </button>
        </div>

        {{-- Status bar --}}
        <div class="px-6 py-3 bg-gray-50 flex flex-wrap gap-4 text-sm">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full {{ $analytics['enabled'] ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                <span class="text-gray-600">Seguimiento: <span class="font-medium {{ $analytics['enabled'] ? 'text-green-700' : 'text-gray-500' }}">{{ $analytics['enabled'] ? 'Activo' : 'Inactivo' }}</span></span>
            </div>
            @if ($analytics['gtm_id'])
                <div class="flex items-center gap-1.5 text-gray-600">
                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>
                    GTM: <code class="font-mono text-xs bg-blue-50 text-blue-700 px-1.5 py-0.5 rounded">{{ $analytics['gtm_id'] }}</code>
                </div>
            @endif
            @if ($analytics['ga4_id'])
                <div class="flex items-center gap-1.5 text-gray-600">
                    <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    GA4: <code class="font-mono text-xs bg-orange-50 text-orange-700 px-1.5 py-0.5 rounded">{{ $analytics['ga4_id'] }}</code>
                </div>
            @endif
            @if ($hasCredentials)
                <div class="flex items-center gap-1.5 text-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    API: credenciales cargadas
                </div>
            @else
                <div class="flex items-center gap-1.5 text-amber-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    API: sin credenciales
                </div>
            @endif
        </div>

        {{-- Settings form (collapsible) --}}
        <div id="settings-panel" class="{{ ($analytics['ga4_id'] || $analytics['gtm_id'] || $hasCredentials) ? 'hidden' : '' }}">
            <form method="POST" action="{{ route('admin.analytics.settings.update') }}" enctype="multipart/form-data" class="px-6 py-5 space-y-5 border-t border-gray-100">
                @csrf

                {{-- Enable toggle --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Activar seguimiento</p>
                        <p class="text-xs text-gray-500 mt-0.5">Inyecta los scripts de GA4/GTM en el sitio público.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="analytics_enabled" value="1" class="sr-only peer"
                            {{ $analytics['enabled'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full
                                    peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px]
                                    after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5
                                    after:transition-all peer-checked:bg-[#2D6A4F]"></div>
                    </label>
                </div>

                <hr class="border-gray-100">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- GTM ID --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Google Tag Manager ID
                            <span class="font-normal text-gray-400">(recomendado)</span>
                        </label>
                        <input type="text" name="analytics_gtm_id"
                            value="{{ old('analytics_gtm_id', $analytics['gtm_id']) }}"
                            placeholder="GTM-XXXXXXX"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                        <p class="text-xs text-gray-400 mt-1">Usa GTM para gestionar todos tus tags sin código.</p>
                    </div>

                    {{-- GA4 ID --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            GA4 Measurement ID
                            <span class="font-normal text-gray-400">(sin GTM)</span>
                        </label>
                        <input type="text" name="analytics_ga4_id"
                            value="{{ old('analytics_ga4_id', $analytics['ga4_id']) }}"
                            placeholder="G-XXXXXXXXXX"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                        <p class="text-xs text-gray-400 mt-1">Solo necesario si no usás GTM.</p>
                    </div>
                </div>

                <hr class="border-gray-100">

                <div>
                    <p class="text-sm font-bold text-gray-700 mb-1">Acceso a la API de Datos (para el dashboard)</p>
                    <p class="text-xs text-gray-500 mb-4">Para mostrar métricas aquí, necesitás crear una cuenta de servicio en Google Cloud Console y darle acceso de lectura a tu propiedad GA4.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        {{-- Property ID --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                GA4 Property ID
                            </label>
                            <input type="text" name="analytics_ga4_property_id"
                                value="{{ old('analytics_ga4_property_id', $analytics['property_id']) }}"
                                placeholder="123456789"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                            <p class="text-xs text-gray-400 mt-1">Número numérico en Administración &rarr; Propiedad.</p>
                        </div>

                        {{-- Service account JSON --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Cuenta de Servicio (JSON)
                            </label>
                            @if ($hasCredentials && $credentialEmail)
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs text-green-700 bg-green-50 border border-green-200 px-2 py-1 rounded">
                                        ✓ {{ $credentialEmail }}
                                    </span>
                                    <form method="POST" action="{{ route('admin.analytics.credentials.delete') }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:underline">Eliminar</button>
                                    </form>
                                </div>
                            @endif
                            <input type="file" name="service_account_json" accept=".json"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-[#2D6A4F] file:text-white">
                            <p class="text-xs text-gray-400 mt-1">Archivo .json descargado de Google Cloud Console.</p>
                        </div>
                    </div>
                </div>

                {{-- Setup guide --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-800">
                    <p class="font-semibold mb-1">¿Cómo configurar la API?</p>
                    <ol class="list-decimal ml-4 space-y-1 text-xs text-blue-700">
                        <li>En Google Cloud Console, creá un proyecto y habilitá <strong>Google Analytics Data API</strong>.</li>
                        <li>Creá una <strong>Cuenta de Servicio</strong> y descargá la clave JSON.</li>
                        <li>En Google Analytics, andá a <strong>Administración → Gestión de accesos a la propiedad</strong> y agregá el email de la cuenta de servicio con rol <strong>Lector</strong>.</li>
                        <li>Ingresá el <strong>Property ID</strong> (número) y subí el archivo JSON aquí.</li>
                    </ol>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                        Guardar configuración
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         NO METRICS — placeholder states
         ═══════════════════════════════════════════ --}}
    @if ($error)
        <div class="bg-red-50 border border-red-200 rounded-xl px-6 py-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-bold text-red-800">Error al obtener métricas de GA4</p>
                <p class="text-xs text-red-600 mt-0.5 font-mono">{{ $error }}</p>
            </div>
        </div>
    @endif

    @if (!$hasCredentials || !$analytics['property_id'])
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-700 mb-2">Dashboard no configurado</h3>
            <p class="text-sm text-gray-500 mb-4">
                @if (!$analytics['property_id'])
                    Ingresá tu GA4 Property ID para habilitar el dashboard de métricas.
                @else
                    Subí las credenciales de cuenta de servicio para acceder a los datos.
                @endif
            </p>
            <button onclick="document.getElementById('settings-panel').classList.remove('hidden'); document.getElementById('settings-panel').scrollIntoView({behavior:'smooth'})"
                class="inline-flex items-center gap-2 bg-[#2D6A4F] text-white font-semibold px-5 py-2.5 rounded-lg text-sm hover:bg-[#1A1A1A] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Configurar ahora
            </button>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════
         METRICS DASHBOARD (when data is available)
         ═══════════════════════════════════════════ --}}
    @if ($metrics)

        {{-- Refresh + period note --}}
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Datos de los últimos <span class="font-semibold text-gray-700">28 días</span>. Actualizados cada hora.</p>
            <form method="POST" action="{{ route('admin.analytics.refresh') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 text-sm text-[#2D6A4F] hover:underline font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Actualizar datos
                </button>
            </form>
        </div>

        {{-- ── Overview cards ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            @php
                $cards = [
                    ['label' => 'Usuarios',       'value' => number_format($metrics['overview']['users']),    'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'blue'],
                    ['label' => 'Sesiones',       'value' => number_format($metrics['overview']['sessions']), 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'color' => 'green'],
                    ['label' => 'Vistas de página','value' => number_format($metrics['overview']['pageviews']),'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'color' => 'purple'],
                    ['label' => 'Tasa de rebote', 'value' => $metrics['overview']['bounce_rate'] . '%',       'icon' => 'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6', 'color' => 'red'],
                    ['label' => 'Duración media', 'value' => $metrics['overview']['avg_dur'],                 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'amber'],
                ];
                $colorMap = [
                    'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'text-blue-500',   'val' => 'text-blue-700'],
                    'green'  => ['bg' => 'bg-green-50',  'icon' => 'text-green-500',  'val' => 'text-[#2D6A4F]'],
                    'purple' => ['bg' => 'bg-purple-50', 'icon' => 'text-purple-500', 'val' => 'text-purple-700'],
                    'red'    => ['bg' => 'bg-red-50',    'icon' => 'text-red-500',    'val' => 'text-red-700'],
                    'amber'  => ['bg' => 'bg-amber-50',  'icon' => 'text-amber-500',  'val' => 'text-amber-700'],
                ];
            @endphp

            @foreach ($cards as $card)
                @php $c = $colorMap[$card['color']]; @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $card['label'] }}</p>
                        <div class="w-8 h-8 {{ $c['bg'] }} rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold {{ $c['val'] }}">{{ $card['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- ── Daily chart ── --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-[#1A1A1A]">Actividad — últimos 30 días</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Sesiones y vistas de página diarias</p>
                </div>
                <div class="flex items-center gap-4 text-xs">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-0.5 bg-[#2D6A4F] inline-block rounded"></span> Sesiones</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-0.5 bg-[#52B788] inline-block rounded"></span> Vistas</span>
                </div>
            </div>
            <div class="p-6">
                <canvas id="dailyChart" height="100"></canvas>
            </div>
        </div>

        {{-- ── Top pages + Traffic sources ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Top pages --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-[#1A1A1A]">Páginas más visitadas</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Últimos 28 días</p>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse ($metrics['topPages'] as $i => $page)
                        <div class="px-6 py-3 flex items-center gap-3">
                            <span class="text-xs font-bold text-gray-400 w-5 text-center">{{ $i + 1 }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-[#1A1A1A] truncate font-mono">{{ $page['path'] }}</p>
                                <p class="text-xs text-gray-400">{{ number_format($page['users']) }} usuarios · {{ sprintf('%d:%02d', intdiv($page['duration'], 60), $page['duration'] % 60) }} min</p>
                            </div>
                            <span class="text-sm font-bold text-[#2D6A4F] flex-shrink-0">{{ number_format($page['views']) }}</span>
                        </div>
                    @empty
                        <div class="px-6 py-6 text-center text-sm text-gray-400">Sin datos disponibles</div>
                    @endforelse
                </div>
            </div>

            {{-- Traffic sources --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-[#1A1A1A]">Fuentes de tráfico</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Por canal — últimos 28 días</p>
                </div>
                <div class="px-6 py-4 space-y-4">
                    @forelse ($metrics['sources'] as $source)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-[#1A1A1A]">{{ $source['channel'] }}</span>
                                <div class="text-right">
                                    <span class="text-sm font-bold text-[#2D6A4F]">{{ number_format($source['sessions']) }}</span>
                                    <span class="text-xs text-gray-400 ml-1">sesiones</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-[#52B788] h-2 rounded-full transition-all duration-500"
                                    style="width: {{ $source['pct'] }}%"></div>
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">{{ number_format($source['users']) }} usuarios · {{ $source['pct'] }}%</p>
                        </div>
                    @empty
                        <div class="text-center py-4 text-sm text-gray-400">Sin datos disponibles</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── Conversion funnel ── --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-[#1A1A1A]">Embudo de conversión</h3>
                <p class="text-xs text-gray-500 mt-0.5">Recorrido del usuario — últimos 28 días</p>
            </div>
            <div class="px-6 py-5 space-y-4">
                @php
                    $funnelColors = ['bg-[#2D6A4F]', 'bg-[#3A7D5F]', 'bg-[#52B788]', 'bg-[#74C99A]', 'bg-[#A8DFC0]'];
                @endphp
                @foreach ($metrics['funnel'] as $i => $step)
                    <div class="flex items-center gap-4">
                        <div class="w-7 h-7 rounded-full {{ $funnelColors[$i] }} flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-xs font-bold">{{ $i + 1 }}</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-[#1A1A1A]">{{ $step['label'] }}</span>
                                <div class="text-right flex-shrink-0">
                                    <span class="text-sm font-bold text-[#1A1A1A]">{{ number_format($step['value']) }}</span>
                                    <span class="text-xs text-gray-400 ml-1">usuarios</span>
                                    @if ($i > 0)
                                        <span class="text-xs font-semibold ml-2 {{ $step['pct'] >= 20 ? 'text-green-600' : 'text-amber-600' }}">
                                            {{ $step['pct'] }}%
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="{{ $funnelColors[$i] }} h-3 rounded-full transition-all duration-700"
                                    style="width: {{ max($step['pct'], 1) }}%"></div>
                            </div>
                        </div>
                    </div>
                    @if (!$loop->last)
                        <div class="ml-3.5 w-0 border-l-2 border-dashed border-gray-200 h-2"></div>
                    @endif
                @endforeach
            </div>
        </div>

    @endif {{-- end $metrics --}}

</div>

{{-- Chart.js --}}
@if ($metrics)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('dailyChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($metrics['daily']['dates']),
            datasets: [
                {
                    label: 'Sesiones',
                    data: @json($metrics['daily']['sessions']),
                    borderColor: '#2D6A4F',
                    backgroundColor: 'rgba(45,106,79,0.08)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#2D6A4F',
                },
                {
                    label: 'Vistas de página',
                    data: @json($metrics['daily']['pageviews']),
                    borderColor: '#52B788',
                    backgroundColor: 'rgba(82,183,136,0.08)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#52B788',
                },
            ],
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1A1A1A',
                    titleFont: { size: 12 },
                    bodyFont: { size: 12 },
                    padding: 10,
                    callbacks: {
                        label: ctx => ' ' + ctx.dataset.label + ': ' + ctx.parsed.y.toLocaleString('es-AR'),
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 }, maxTicksLimit: 10 },
                },
                y: {
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: { font: { size: 10 }, callback: v => v.toLocaleString('es-AR') },
                    beginAtZero: true,
                },
            },
        },
    });
</script>
@endif

<script>
    // Toggle settings panel
    document.querySelectorAll('[onclick*="settings-panel"]').forEach(btn => {
        btn.addEventListener('click', function () {
            const panel = document.getElementById('settings-panel');
            if (!panel.classList.contains('hidden')) {
                panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
</script>
@endsection
