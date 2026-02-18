@extends('layouts.admin')
@section('title', 'Analíticas de Hoteles')
@section('header', 'Analíticas de Hoteles')

@section('content')

{{-- Summary cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total visitas</p>
        <p class="text-3xl font-bold text-[#1A1A1A]">{{ number_format($totalViews) }}</p>
        <p class="text-xs text-gray-400 mt-1">todas las épocas</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Este mes</p>
        <p class="text-3xl font-bold text-[#2D6A4F]">{{ number_format($monthViews) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('F Y') }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Hoy</p>
        <p class="text-3xl font-bold text-[#52B788]">{{ number_format($todayViews) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('d \d\e F') }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Contactos recibidos</p>
        <p class="text-3xl font-bold text-amber-500">{{ number_format($totalContacts) }}</p>
        <p class="text-xs text-gray-400 mt-1">por formulario</p>
    </div>
</div>

{{-- 30-day chart --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5">Visitas — últimos 30 días</p>
    @php $maxDay = max(1, max($days->values()->toArray())); @endphp
    <div class="flex items-end gap-1 h-28">
        @foreach ($days as $date => $count)
        <div class="flex-1 flex flex-col items-center gap-1 group relative">
            <div class="w-full bg-[#2D6A4F]/15 rounded-t transition group-hover:bg-[#2D6A4F]/30"
                 style="height: {{ $maxDay > 0 ? round(($count / $maxDay) * 100) : 0 }}%"
                 title="{{ $date }}: {{ $count }} visitas">
            </div>
            {{-- Tooltip on hover --}}
            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#1A1A1A] text-white text-[0.6rem] font-semibold px-1.5 py-0.5 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition pointer-events-none z-10">
                {{ $count }}
            </div>
        </div>
        @endforeach
    </div>
    <div class="flex justify-between mt-2 text-[0.65rem] text-gray-300 font-medium">
        <span>{{ now()->subDays(29)->format('d/m') }}</span>
        <span>{{ now()->subDays(14)->format('d/m') }}</span>
        <span>{{ now()->format('d/m') }}</span>
    </div>
</div>

{{-- Hotel performance table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-100">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Rendimiento por hotel</p>
    </div>
    @php $maxMonthViews = max(1, $hotels->max('month_views')); @endphp
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left py-3 px-4 font-semibold text-gray-500">Hotel</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-500 hidden sm:table-cell">Este mes</th>
                <th class="text-right py-3 px-4 font-semibold text-gray-500">Total visitas</th>
                <th class="text-right py-3 px-4 font-semibold text-gray-500">Contactos</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($hotels as $hotel)
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4">
                    <a href="{{ route('admin.hoteles.show', $hotel) }}" class="font-semibold text-[#1A1A1A] hover:text-[#2D6A4F] transition">
                        {{ $hotel->name }}
                    </a>
                    @if ($hotel->hotel_type)
                        <span class="ml-1.5 text-[0.65rem] text-gray-400">{{ $hotel->hotel_type }}</span>
                    @endif
                </td>
                <td class="py-3 px-4 hidden sm:table-cell">
                    <div class="flex items-center gap-2">
                        <div class="flex-1 bg-gray-100 rounded-full h-1.5 max-w-[120px]">
                            <div class="bg-[#2D6A4F] h-1.5 rounded-full"
                                 style="width: {{ round(($hotel->month_views / $maxMonthViews) * 100) }}%"></div>
                        </div>
                        <span class="text-gray-600 text-xs font-medium w-6 text-right">{{ $hotel->month_views }}</span>
                    </div>
                </td>
                <td class="py-3 px-4 text-right font-semibold text-gray-700">{{ number_format($hotel->total_views) }}</td>
                <td class="py-3 px-4 text-right">
                    <span class="inline-block bg-amber-50 text-amber-700 font-semibold text-xs px-2 py-0.5 rounded-full">
                        {{ $hotel->total_contacts }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-10 text-center text-gray-400">No hay hoteles aún.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Recent contacts --}}
@if ($recentContacts->isNotEmpty())
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Contactos recientes</p>
        <a href="{{ route('admin.hotel-contactos.index') }}" class="text-xs text-[#2D6A4F] hover:underline font-semibold">Ver todos →</a>
    </div>
    <div class="divide-y divide-gray-50">
        @foreach ($recentContacts as $contact)
        <div class="px-6 py-4 flex items-start gap-4">
            <div class="w-8 h-8 rounded-full bg-[#2D6A4F]/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                <span class="text-[#2D6A4F] text-xs font-bold">{{ strtoupper(substr($contact->sender_name, 0, 1)) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-semibold text-sm text-[#1A1A1A]">{{ $contact->sender_name }}</span>
                    <span class="text-gray-400 text-xs">→ {{ $contact->hotel->name }}</span>
                    @if (! $contact->email_sent)
                        <span class="text-[0.65rem] bg-red-50 text-red-500 px-1.5 py-0.5 rounded font-semibold">Email no enviado</span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-0.5">{{ $contact->sender_email }}{{ $contact->sender_phone ? ' · ' . $contact->sender_phone : '' }}</p>
                <p class="text-sm text-gray-600 mt-1 truncate">{{ $contact->message }}</p>
            </div>
            <span class="text-xs text-gray-400 flex-shrink-0">{{ $contact->created_at->diffForHumans() }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection
