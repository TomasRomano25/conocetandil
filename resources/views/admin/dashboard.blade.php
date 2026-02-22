@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@php
    function fmoney(float $n): string {
        return '$' . number_format($n, 0, ',', '.');
    }
@endphp

@section('content')

{{-- Tab switcher --}}
<div class="flex gap-1 mb-6 bg-gray-100 rounded-xl p-1 w-fit">
    <button onclick="switchTab('overview')" id="tab-overview"
        class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold transition">
        Resumen
    </button>
    <button onclick="switchTab('traffic')" id="tab-traffic"
        class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold transition">
        Tráfico
    </button>
</div>

{{-- OVERVIEW TAB --}}
<div id="panel-overview" class="space-y-6">

{{-- ════════════════════════════════════════════════════════════
     HERO KPI CARDS
     ════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

    {{-- Revenue --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#2D6A4F] to-[#52B788]"></div>
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 bg-[#2D6A4F]/10 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            @if($revenueMoM !== 0)
            <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $revenueMoM > 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600' }}">
                {{ $revenueMoM > 0 ? '+' : '' }}{{ $revenueMoM }}%
            </span>
            @endif
        </div>
        <p class="text-2xl font-bold text-[#1A1A1A] leading-none mb-1">{{ fmoney($totalRevenue) }}</p>
        <p class="text-xs text-gray-500 font-medium">Ingresos totales</p>
        <p class="text-xs text-gray-400 mt-1">Este mes: {{ fmoney($thisMonthRevenue) }}</p>
    </div>

    {{-- Premium Members --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-violet-500 to-purple-400"></div>
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"/>
                </svg>
            </div>
            <span class="text-xs font-medium text-gray-400">+{{ $users['new_month'] }} este mes</span>
        </div>
        <p class="text-2xl font-bold text-[#1A1A1A] leading-none mb-1">{{ $users['premium'] }}</p>
        <p class="text-xs text-gray-500 font-medium">Suscripciones activas</p>
        <p class="text-xs text-gray-400 mt-1">{{ $users['total'] }} usuarios totales</p>
    </div>

    {{-- Active Hotels --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 to-orange-400"></div>
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <span class="text-xs font-medium text-gray-400">{{ $hotelViewsTotal30 }} vistas/30d</span>
        </div>
        <p class="text-2xl font-bold text-[#1A1A1A] leading-none mb-1">{{ $hotels['active'] }}</p>
        <p class="text-xs text-gray-500 font-medium">Hoteles activos</p>
        <p class="text-xs text-gray-400 mt-1">{{ $hotels['total'] }} registrados en total</p>
    </div>

    {{-- Pending Actions --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 relative overflow-hidden {{ $totalPending > 0 ? 'ring-2 ring-amber-300' : '' }}">
        <div class="absolute top-0 left-0 right-0 h-1 {{ $totalPending > 0 ? 'bg-gradient-to-r from-amber-400 to-yellow-300' : 'bg-gray-100' }}"></div>
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 {{ $totalPending > 0 ? 'bg-amber-50' : 'bg-gray-50' }} rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 {{ $totalPending > 0 ? 'text-amber-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            @if($totalPending > 0)
            <span class="text-xs font-bold px-2 py-1 rounded-full bg-amber-100 text-amber-700">Requiere acción</span>
            @endif
        </div>
        <p class="text-2xl font-bold text-[#1A1A1A] leading-none mb-1">{{ $totalPending }}</p>
        <p class="text-xs text-gray-500 font-medium">Acciones pendientes</p>
        <p class="text-xs text-gray-400 mt-1">
            {{ $membershipOrders['pending'] }} membresías · {{ $hotelOrders['pending'] }} hoteles · {{ $hotels['pending'] }} aprobaciones
        </p>
    </div>

</div>


{{-- ════════════════════════════════════════════════════════════
     REVENUE CHART
     ════════════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-sm font-bold text-[#1A1A1A]">Ingresos por mes</h2>
            <p class="text-xs text-gray-400 mt-0.5">Últimos 12 meses · Membresías + Hoteles</p>
        </div>
        <div class="flex items-center gap-4 text-xs text-gray-500">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-[#2D6A4F] inline-block"></span>Membresías</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-amber-400 inline-block"></span>Hoteles</span>
        </div>
    </div>
    <div class="h-56">
        <canvas id="revenueChart"></canvas>
    </div>
</div>


{{-- ════════════════════════════════════════════════════════════
     ORDERS ROW
     ════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

    {{-- Membership Orders --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-[#1A1A1A]">Pedidos Membresías</h3>
            <a href="{{ route('admin.pedidos.index') }}" class="text-xs text-[#2D6A4F] hover:underline font-medium">Ver todos →</a>
        </div>
        <div class="flex items-center gap-4">
            <div class="w-20 h-20 flex-shrink-0">
                <canvas id="membershipDonut"></canvas>
            </div>
            <div class="flex-1 space-y-1.5">
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>Completados</span>
                    <span class="font-bold text-[#1A1A1A]">{{ $membershipOrders['completed'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>Pendientes</span>
                    <span class="font-bold text-[#1A1A1A]">{{ $membershipOrders['pending'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span>Cancelados</span>
                    <span class="font-bold text-[#1A1A1A]">{{ $membershipOrders['cancelled'] }}</span>
                </div>
                <div class="pt-1 border-t border-gray-100 flex items-center justify-between text-xs">
                    <span class="text-gray-500">Conversión</span>
                    <span class="font-bold text-[#2D6A4F]">{{ $membershipOrders['conversion'] }}%</span>
                </div>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-gray-100">
            <p class="text-xs text-gray-500">Ingresos confirmados</p>
            <p class="text-base font-bold text-[#1A1A1A]">{{ fmoney($membershipRevenue) }}</p>
        </div>
    </div>

    {{-- Hotel Orders --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-[#1A1A1A]">Pedidos Hoteles</h3>
            <a href="{{ route('admin.hotel-pedidos.index') }}" class="text-xs text-[#2D6A4F] hover:underline font-medium">Ver todos →</a>
        </div>
        <div class="flex items-center gap-4">
            <div class="w-20 h-20 flex-shrink-0">
                <canvas id="hotelOrderDonut"></canvas>
            </div>
            <div class="flex-1 space-y-1.5">
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>Completados</span>
                    <span class="font-bold text-[#1A1A1A]">{{ $hotelOrders['completed'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>Pendientes</span>
                    <span class="font-bold text-[#1A1A1A]">{{ $hotelOrders['pending'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span>Cancelados</span>
                    <span class="font-bold text-[#1A1A1A]">{{ $hotelOrders['cancelled'] }}</span>
                </div>
                <div class="pt-1 border-t border-gray-100 flex items-center justify-between text-xs">
                    <span class="text-gray-500">Conversión</span>
                    <span class="font-bold text-[#2D6A4F]">{{ $hotelOrders['conversion'] }}%</span>
                </div>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-gray-100">
            <p class="text-xs text-gray-500">Ingresos confirmados</p>
            <p class="text-base font-bold text-[#1A1A1A]">{{ fmoney($hotelRevenue) }}</p>
        </div>
    </div>

    {{-- Lost Checkouts --}}
    <div class="rounded-2xl border shadow-sm p-5 {{ $totalLost > 0 ? 'bg-red-50 border-red-200' : 'bg-white border-gray-100' }}">
        <div class="flex items-start gap-3 mb-4">
            <div class="w-10 h-10 {{ $totalLost > 0 ? 'bg-red-100' : 'bg-gray-100' }} rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 {{ $totalLost > 0 ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold {{ $totalLost > 0 ? 'text-red-900' : 'text-[#1A1A1A]' }}">Checkouts perdidos</h3>
                <p class="text-xs {{ $totalLost > 0 ? 'text-red-600' : 'text-gray-400' }}">Pendientes hace +72 horas</p>
            </div>
        </div>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <p class="text-3xl font-bold {{ $totalLost > 0 ? 'text-red-700' : 'text-gray-300' }}">{{ $totalLost }}</p>
                <div class="text-right">
                    <p class="text-xs {{ $totalLost > 0 ? 'text-red-500' : 'text-gray-400' }}">Valor potencial</p>
                    <p class="text-base font-bold {{ $totalLost > 0 ? 'text-red-700' : 'text-gray-300' }}">{{ fmoney($totalLostValue) }}</p>
                </div>
            </div>
            @if($totalLost > 0)
            <div class="space-y-1.5 text-xs">
                @if($lostMembership > 0)
                <div class="flex items-center justify-between bg-red-100 rounded-lg px-3 py-2">
                    <span class="text-red-700 font-medium">{{ $lostMembership }} membresía{{ $lostMembership > 1 ? 's' : '' }}</span>
                    <span class="text-red-600 font-bold">{{ fmoney($lostMembershipValue) }}</span>
                </div>
                @endif
                @if($lostHotel > 0)
                <div class="flex items-center justify-between bg-red-100 rounded-lg px-3 py-2">
                    <span class="text-red-700 font-medium">{{ $lostHotel }} hotel{{ $lostHotel > 1 ? 'es' : '' }}</span>
                    <span class="text-red-600 font-bold">{{ fmoney($lostHotelValue) }}</span>
                </div>
                @endif
            </div>
            <a href="{{ route('admin.pedidos.index') }}" class="block w-full text-center text-xs font-semibold text-red-700 bg-red-100 hover:bg-red-200 rounded-lg px-3 py-2 transition">
                Revisar pedidos pendientes →
            </a>
            @else
            <p class="text-xs text-gray-400 text-center py-2">No hay checkouts perdidos ✓</p>
            @endif
        </div>
    </div>

</div>


{{-- ════════════════════════════════════════════════════════════
     CHARTS ROW: USERS + HOTEL VIEWS
     ════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    {{-- User registrations --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-[#1A1A1A]">Nuevos registros</h3>
                <p class="text-xs text-gray-400">Últimos 30 días</p>
            </div>
            <div class="text-right">
                <p class="text-xl font-bold text-[#1A1A1A]">{{ $users['new_month'] }}</p>
                <p class="text-xs text-gray-400">este mes</p>
            </div>
        </div>
        <div class="h-36">
            <canvas id="userChart"></canvas>
        </div>
        <div class="grid grid-cols-3 gap-2 mt-3 pt-3 border-t border-gray-100 text-center">
            <div>
                <p class="text-base font-bold text-[#1A1A1A]">{{ $users['total'] }}</p>
                <p class="text-xs text-gray-400">Total</p>
            </div>
            <div>
                <p class="text-base font-bold text-violet-600">{{ $users['premium'] }}</p>
                <p class="text-xs text-gray-400">Premium</p>
            </div>
            <div>
                <p class="text-base font-bold text-[#1A1A1A]">{{ $users['new_week'] }}</p>
                <p class="text-xs text-gray-400">Esta semana</p>
            </div>
        </div>
    </div>

    {{-- Hotel views --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-[#1A1A1A]">Vistas de hoteles</h3>
                <p class="text-xs text-gray-400">Últimos 30 días</p>
            </div>
            <div class="text-right">
                <p class="text-xl font-bold text-[#1A1A1A]">{{ $hotelViewsTotal30 }}</p>
                <p class="text-xs text-gray-400">vistas totales</p>
            </div>
        </div>
        <div class="h-36">
            <canvas id="hotelViewChart"></canvas>
        </div>
        <div class="grid grid-cols-3 gap-2 mt-3 pt-3 border-t border-gray-100 text-center">
            <div>
                <p class="text-base font-bold text-[#1A1A1A]">{{ $hotels['active'] }}</p>
                <p class="text-xs text-gray-400">Activos</p>
            </div>
            <div>
                <p class="text-base font-bold text-amber-600">{{ $hotels['featured'] }}</p>
                <p class="text-xs text-gray-400">Destacados</p>
            </div>
            <div>
                <p class="text-base font-bold text-[#1A1A1A]">{{ $hotels['pending'] }}</p>
                <p class="text-xs text-gray-400">Pendientes</p>
            </div>
        </div>
    </div>

</div>


{{-- ════════════════════════════════════════════════════════════
     HOTELS STATUS + MESSAGES + PROMOTIONS
     ════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

    {{-- Hotels status breakdown --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-[#1A1A1A]">Estado de hoteles</h3>
            <a href="{{ route('admin.hoteles.index') }}" class="text-xs text-[#2D6A4F] hover:underline font-medium">Gestionar →</a>
        </div>
        <div class="space-y-2">
            @foreach([
                ['Activos',    $hotels['active'],    'bg-green-500',  'bg-green-50',  'text-green-700'],
                ['Pendientes', $hotels['pending'],   'bg-amber-400',  'bg-amber-50',  'text-amber-700'],
                ['Rechazados', $hotels['rejected'],  'bg-red-400',    'bg-red-50',    'text-red-700'],
                ['Suspendidos',$hotels['suspended'], 'bg-gray-400',   'bg-gray-50',   'text-gray-600'],
            ] as [$label, $count, $bar, $bg, $text])
            @php $pct = $hotels['total'] > 0 ? round(($count / $hotels['total']) * 100) : 0; @endphp
            <div class="{{ $bg }} rounded-xl px-3 py-2.5">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium {{ $text }}">{{ $label }}</span>
                    <span class="text-xs font-bold {{ $text }}">{{ $count }}</span>
                </div>
                <div class="h-1.5 bg-white/60 rounded-full overflow-hidden">
                    <div class="h-full {{ $bar }} rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @if($hotelsByTier->count())
        <div class="mt-3 pt-3 border-t border-gray-100">
            <p class="text-xs font-semibold text-gray-500 mb-2">Por plan (activos)</p>
            <div class="space-y-1">
                @foreach($hotelsByTier as $tier)
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-600">{{ $tier->plan_name }}</span>
                    <span class="font-bold text-[#1A1A1A]">{{ $tier->count }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Messages --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-[#1A1A1A]">Mensajes</h3>
            <a href="{{ route('admin.mensajes.index') }}" class="text-xs text-[#2D6A4F] hover:underline font-medium">Ver bandeja →</a>
        </div>
        <div class="flex items-center gap-4 mb-4">
            <div class="flex-1 bg-[#2D6A4F]/5 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-[#2D6A4F]">{{ $messages['unread'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Sin leer</p>
            </div>
            <div class="flex-1 bg-gray-50 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-[#1A1A1A]">{{ $messages['total'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Total</p>
            </div>
        </div>
        <div class="space-y-2 text-xs">
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Hoy</span>
                <span class="font-bold text-[#1A1A1A]">{{ $messages['today'] }}</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Esta semana</span>
                <span class="font-bold text-[#1A1A1A]">{{ $messages['week'] }}</span>
            </div>
            <div class="flex items-center justify-between py-2">
                <span class="text-gray-500">Tasa de lectura</span>
                @php $readRate = $messages['total'] > 0 ? round((($messages['total'] - $messages['unread']) / $messages['total']) * 100) : 100; @endphp
                <span class="font-bold text-[#2D6A4F]">{{ $readRate }}%</span>
            </div>
        </div>
        @if($messages['unread'] > 0)
        <a href="{{ route('admin.mensajes.index') }}" class="mt-3 block w-full text-center text-xs font-semibold text-[#2D6A4F] bg-[#2D6A4F]/10 hover:bg-[#2D6A4F]/20 rounded-lg px-3 py-2 transition">
            Leer {{ $messages['unread'] }} mensaje{{ $messages['unread'] > 1 ? 's' : '' }} →
        </a>
        @endif
    </div>

    {{-- Promotions --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-[#1A1A1A]">Promociones</h3>
            <a href="{{ route('admin.promociones.index') }}" class="text-xs text-[#2D6A4F] hover:underline font-medium">Gestionar →</a>
        </div>
        <div class="grid grid-cols-2 gap-2 mb-4">
            <div class="bg-green-50 rounded-xl p-3 text-center">
                <p class="text-xl font-bold text-green-700">{{ $promotionsStats['active'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Activas</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-3 text-center">
                <p class="text-xl font-bold text-[#1A1A1A]">{{ $promotionsStats['total_uses'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Usos totales</p>
            </div>
        </div>
        <div class="space-y-2 text-xs">
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Total promociones</span>
                <span class="font-bold text-[#1A1A1A]">{{ $promotionsStats['total'] }}</span>
            </div>
            <div class="flex items-center justify-between py-2">
                <span class="text-gray-500">Descuento otorgado</span>
                <span class="font-bold text-red-600">-{{ fmoney($promotionsStats['total_discount']) }}</span>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-gray-100">
            <p class="text-xs text-gray-400">Impacto en ingresos</p>
            @php $discountPct = $totalRevenue > 0 ? round(($promotionsStats['total_discount'] / ($totalRevenue + $promotionsStats['total_discount'])) * 100, 1) : 0; @endphp
            <div class="flex items-center gap-2 mt-1">
                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-red-400 rounded-full" style="width: {{ min($discountPct, 100) }}%"></div>
                </div>
                <span class="text-xs font-bold text-red-600">{{ $discountPct }}%</span>
            </div>
        </div>
    </div>

</div>


{{-- ════════════════════════════════════════════════════════════
     RECENT ORDERS
     ════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    {{-- Recent membership orders --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-bold text-[#1A1A1A]">Últimos pedidos · Membresías</h3>
            <a href="{{ route('admin.pedidos.index') }}" class="text-xs text-[#2D6A4F] hover:underline font-medium">Ver todos →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentOrders as $order)
            <div class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition">
                <div class="w-8 h-8 bg-[#2D6A4F]/10 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold text-[#2D6A4F]">
                    {{ strtoupper(substr($order->user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-[#1A1A1A] truncate">{{ $order->user->name ?? '—' }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $order->plan->name ?? '—' }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs font-bold text-[#1A1A1A]">{{ fmoney($order->total) }}</p>
                    <span class="inline-flex text-xs px-2 py-0.5 rounded-full font-medium
                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : ($order->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                        {{ $order->statusLabel() }}
                    </span>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-xs text-gray-400">Sin pedidos registrados</div>
            @endforelse
        </div>
    </div>

    {{-- Recent hotel orders --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-bold text-[#1A1A1A]">Últimos pedidos · Hoteles</h3>
            <a href="{{ route('admin.hotel-pedidos.index') }}" class="text-xs text-[#2D6A4F] hover:underline font-medium">Ver todos →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentHotelOrders as $order)
            <div class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition">
                <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold text-amber-700">
                    {{ strtoupper(substr($order->hotel->name ?? 'H', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-[#1A1A1A] truncate">{{ $order->hotel->name ?? '—' }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $order->plan->name ?? '—' }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs font-bold text-[#1A1A1A]">{{ fmoney($order->amount) }}</p>
                    <span class="inline-flex text-xs px-2 py-0.5 rounded-full font-medium
                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : ($order->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                        {{ $order->statusLabel() }}
                    </span>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-xs text-gray-400">Sin pedidos registrados</div>
            @endforelse
        </div>
    </div>

</div>


{{-- ════════════════════════════════════════════════════════════
     CONTENT + QUICK LINKS
     ════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
    <a href="{{ route('admin.lugares.index') }}"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md hover:border-[#52B788] transition group">
        <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-[#2D6A4F]/20 transition">
            <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-base font-bold text-[#1A1A1A]">{{ $totalLugares }}</p>
            <p class="text-xs text-gray-400">Lugares</p>
        </div>
    </a>
    <a href="{{ route('admin.usuarios.index') }}"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md hover:border-violet-300 transition group">
        <div class="w-9 h-9 bg-violet-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-violet-100 transition">
            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-base font-bold text-[#1A1A1A]">{{ $users['total'] }}</p>
            <p class="text-xs text-gray-400">Usuarios</p>
        </div>
    </a>
    <a href="{{ route('admin.hoteles.index') }}"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md hover:border-amber-300 transition group">
        <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-amber-100 transition">
            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <div>
            <p class="text-base font-bold text-[#1A1A1A]">{{ $hotels['total'] }}</p>
            <p class="text-xs text-gray-400">Hoteles</p>
        </div>
    </a>
    <a href="{{ route('admin.analytics.dashboard') }}"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md hover:border-blue-300 transition group">
        <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-100 transition">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <div>
            <p class="text-base font-bold text-[#1A1A1A]">GA4</p>
            <p class="text-xs text-gray-400">Analytics</p>
        </div>
    </a>
</div>

</div>{{-- end panel-overview --}}


{{-- ════════════════════════════════════════════════════════════
     TRAFFIC TAB
     ════════════════════════════════════════════════════════════ --}}
<div id="panel-traffic" class="hidden space-y-6">

    {{-- Traffic KPI cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['Vistas hoy',        $trafficKpis['today'], 'text-[#2D6A4F]', 'bg-[#2D6A4F]/10'],
            ['Esta semana',       $trafficKpis['week'],  'text-blue-600',  'bg-blue-50'],
            ['Este mes',          $trafficKpis['month'], 'text-violet-600','bg-violet-50'],
            ['Total histórico',   $trafficKpis['total'], 'text-gray-700',  'bg-gray-100'],
        ] as [$label, $value, $textColor, $bgColor])
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="w-10 h-10 {{ $bgColor }} rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 {{ $textColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-[#1A1A1A]">{{ number_format($value) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $label }}</p>
        </div>
        @endforeach
    </div>

    {{-- Daily traffic chart --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-sm font-bold text-[#1A1A1A]">Visitas diarias</h2>
                <p class="text-xs text-gray-400 mt-0.5">Últimos 30 días · por tipo de página</p>
            </div>
            <div class="flex items-center gap-4 text-xs text-gray-500">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-[#2D6A4F] inline-block"></span>Lugares</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-amber-400 inline-block"></span>Hoteles</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-blue-400 inline-block"></span>Otras</span>
            </div>
        </div>
        <div class="h-56">
            <canvas id="trafficChart"></canvas>
        </div>
    </div>

    {{-- Top pages + top lugares --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Top pages by views --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-bold text-[#1A1A1A]">Páginas más visitadas</h3>
                <p class="text-xs text-gray-400 mt-0.5">Vistas únicas por sesión/día · históricas</p>
            </div>
            @php $maxPageViews = $topPages->max('total') ?: 1; @endphp
            <div class="divide-y divide-gray-50">
                @forelse($topPages as $pg)
                <div class="px-5 py-3">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-semibold text-[#1A1A1A]">{{ $pg['page'] }}</span>
                        <div class="flex items-center gap-3 text-xs">
                            <span class="text-gray-400">{{ $pg['month'] }} este mes</span>
                            <span class="font-bold text-[#1A1A1A] min-w-[2rem] text-right">{{ number_format($pg['total']) }}</span>
                        </div>
                    </div>
                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-[#52B788] rounded-full"
                             style="width: {{ round(($pg['total'] / $maxPageViews) * 100) }}%"></div>
                    </div>
                </div>
                @empty
                <div class="px-5 py-10 text-center text-xs text-gray-400">Sin datos de tráfico todavía.</div>
                @endforelse
            </div>
        </div>

        {{-- Top lugares --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-bold text-[#1A1A1A]">Lugares más visitados</h3>
                <p class="text-xs text-gray-400 mt-0.5">Vistas únicas históricas · top 10</p>
            </div>
            @php $maxLugarViews = collect($topLugares)->max('total') ?: 1; @endphp
            <div class="divide-y divide-gray-50">
                @forelse($topLugares as $i => $item)
                <div class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition">
                    <span class="text-xs font-bold text-gray-300 w-4 text-center">{{ $i + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-[#1A1A1A] truncate">{{ $item['title'] }}</p>
                        <div class="mt-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-[#2D6A4F] rounded-full"
                                 style="width: {{ round(($item['total'] / $maxLugarViews) * 100) }}%"></div>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs font-bold text-[#1A1A1A]">{{ number_format($item['total']) }}</p>
                        <p class="text-xs text-gray-400">{{ $item['month'] }} mes</p>
                    </div>
                </div>
                @empty
                <div class="px-5 py-10 text-center text-xs text-gray-400">Sin visitas registradas todavía.</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Top hoteles --}}
    @if($topHoteles->count())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-bold text-[#1A1A1A]">Hoteles más visitados</h3>
            <p class="text-xs text-gray-400 mt-0.5">Vistas únicas históricas · top 10</p>
        </div>
        @php $maxHotelViews = collect($topHoteles)->max('total') ?: 1; @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-gray-50">
            @foreach($topHoteles as $i => $item)
            <div class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition">
                <span class="text-xs font-bold text-gray-300 w-4 text-center">{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-[#1A1A1A] truncate">{{ $item['name'] }}</p>
                    <div class="mt-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-400 rounded-full"
                             style="width: {{ round(($item['total'] / $maxHotelViews) * 100) }}%"></div>
                    </div>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs font-bold text-[#1A1A1A]">{{ number_format($item['total']) }}</p>
                    <p class="text-xs text-gray-400">{{ $item['month'] }} mes</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <p class="text-xs text-gray-400 text-center pb-2">
        Las vistas se registran una vez por sesión por día · sin datos de visitantes anónimos previos a esta versión.
        @if($trafficKpis['total'] === 0) Comenzará a acumular datos desde ahora. @endif
    </p>

</div>{{-- end panel-traffic --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Tab switching ─────────────────────────────────────────────────
function switchTab(tab) {
    ['overview', 'traffic'].forEach(t => {
        document.getElementById('panel-' + t).classList.toggle('hidden', t !== tab);
        const btn = document.getElementById('tab-' + t);
        if (t === tab) {
            btn.classList.add('bg-white', 'shadow-sm', 'text-[#1A1A1A]');
            btn.classList.remove('text-gray-500');
        } else {
            btn.classList.remove('bg-white', 'shadow-sm', 'text-[#1A1A1A]');
            btn.classList.add('text-gray-500');
        }
    });
    localStorage.setItem('dash_tab', tab);
    // init traffic chart lazily on first open
    if (tab === 'traffic' && !window._trafficChartInit) {
        window._trafficChartInit = true;
        initTrafficChart();
    }
}
// Restore last tab
document.addEventListener('DOMContentLoaded', () => {
    const saved = localStorage.getItem('dash_tab') || 'overview';
    switchTab(saved);
});

Chart.defaults.font.family = 'Inter, sans-serif';
Chart.defaults.color = '#6b7280';

const GREEN      = '#2D6A4F';
const GREEN_LIGHT = '#52B788';
const AMBER      = '#f59e0b';
const RED        = '#ef4444';
const GRAY       = '#e5e7eb';

// ── Revenue chart ─────────────────────────────────────────────────
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: @json($revenueLabels),
        datasets: [
            {
                label: 'Membresías',
                data: @json($revenueMembership),
                backgroundColor: GREEN,
                borderRadius: 4,
                borderSkipped: false,
            },
            {
                label: 'Hoteles',
                data: @json($revenueHotel),
                backgroundColor: AMBER,
                borderRadius: 4,
                borderSkipped: false,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' $' + ctx.parsed.y.toLocaleString('es-AR')
                }
            }
        },
        scales: {
            x: {
                stacked: true,
                grid: { display: false },
                ticks: { font: { size: 11 } }
            },
            y: {
                stacked: true,
                grid: { color: '#f3f4f6' },
                ticks: {
                    font: { size: 11 },
                    callback: v => '$' + (v >= 1000 ? (v/1000).toFixed(0) + 'k' : v)
                }
            }
        }
    }
});

// ── Membership donut ──────────────────────────────────────────────
new Chart(document.getElementById('membershipDonut'), {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [
                {{ $membershipOrders['completed'] }},
                {{ $membershipOrders['pending'] }},
                {{ $membershipOrders['cancelled'] }}
            ],
            backgroundColor: ['#22c55e', AMBER, RED],
            borderWidth: 0,
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        cutout: '68%',
        plugins: { legend: { display: false }, tooltip: { enabled: false } }
    }
});

// ── Hotel orders donut ────────────────────────────────────────────
new Chart(document.getElementById('hotelOrderDonut'), {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [
                {{ $hotelOrders['completed'] }},
                {{ $hotelOrders['pending'] }},
                {{ $hotelOrders['cancelled'] }}
            ],
            backgroundColor: ['#22c55e', AMBER, RED],
            borderWidth: 0,
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        cutout: '68%',
        plugins: { legend: { display: false }, tooltip: { enabled: false } }
    }
});

// ── User registrations chart ──────────────────────────────────────
new Chart(document.getElementById('userChart'), {
    type: 'line',
    data: {
        labels: @json($userLabels),
        datasets: [{
            data: @json($userCounts),
            borderColor: '#7c3aed',
            backgroundColor: 'rgba(124,58,237,0.08)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointRadius: 0,
            pointHoverRadius: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                grid: { display: false },
                ticks: { font: { size: 10 }, maxTicksLimit: 8 }
            },
            y: {
                grid: { color: '#f3f4f6' },
                ticks: { font: { size: 10 }, stepSize: 1 },
                beginAtZero: true
            }
        }
    }
});

// ── Traffic chart (lazy) ─────────────────────────────────────────
function initTrafficChart() {
    new Chart(document.getElementById('trafficChart'), {
        type: 'bar',
        data: {
            labels: @json($trafficDailyLabels),
            datasets: [
                {
                    label: 'Lugares',
                    data: @json($trafficDailyGroups['lugar']),
                    backgroundColor: GREEN,
                    borderRadius: 3,
                    borderSkipped: false,
                },
                {
                    label: 'Hoteles',
                    data: @json($trafficDailyGroups['hotel']),
                    backgroundColor: AMBER,
                    borderRadius: 3,
                    borderSkipped: false,
                },
                {
                    label: 'Otras',
                    data: @json($trafficDailyGroups['otros']),
                    backgroundColor: '#60a5fa',
                    borderRadius: 3,
                    borderSkipped: false,
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                x: {
                    stacked: true,
                    grid: { display: false },
                    ticks: { font: { size: 10 }, maxTicksLimit: 10 }
                },
                y: {
                    stacked: true,
                    grid: { color: '#f3f4f6' },
                    ticks: { font: { size: 10 }, stepSize: 1 },
                    beginAtZero: true
                }
            }
        }
    });
}

// ── Hotel views chart ─────────────────────────────────────────────
new Chart(document.getElementById('hotelViewChart'), {
    type: 'line',
    data: {
        labels: @json($hotelViewLabels),
        datasets: [{
            data: @json($hotelViewCounts),
            borderColor: AMBER,
            backgroundColor: 'rgba(245,158,11,0.08)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointRadius: 0,
            pointHoverRadius: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                grid: { display: false },
                ticks: { font: { size: 10 }, maxTicksLimit: 8 }
            },
            y: {
                grid: { color: '#f3f4f6' },
                ticks: { font: { size: 10 }, stepSize: 1 },
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection
