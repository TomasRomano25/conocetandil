@extends('layouts.admin')
@section('title', 'Contactos de Hoteles')
@section('header', 'Contactos de Hoteles')

@section('content')

{{-- Filters --}}
<div class="flex flex-wrap items-center gap-3 mb-6">
    <form method="GET" action="{{ route('admin.hotel-contactos.index') }}" class="flex items-center gap-2">
        <select name="hotel_id" onchange="this.form.submit()"
            class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            <option value="">Todos los hoteles</option>
            @foreach ($hotels as $h)
            <option value="{{ $h->id }}" {{ request('hotel_id') == $h->id ? 'selected' : '' }}>{{ $h->name }}</option>
            @endforeach
        </select>
    </form>
    <span class="text-sm text-gray-400">{{ $contacts->total() }} {{ $contacts->total() === 1 ? 'contacto' : 'contactos' }}</span>
</div>

{{-- Grid --}}
<div class="space-y-3">
    @forelse ($contacts as $contact)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5"
         x-data="{ open: false }" x-cloak>

        <div class="flex items-start gap-4">
            {{-- Avatar --}}
            <div class="w-10 h-10 rounded-full bg-[#2D6A4F]/10 flex items-center justify-center flex-shrink-0">
                <span class="text-[#2D6A4F] font-bold text-sm">{{ strtoupper(substr($contact->sender_name, 0, 1)) }}</span>
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-1">
                    <span class="font-semibold text-[#1A1A1A]">{{ $contact->sender_name }}</span>
                    <span class="text-xs text-gray-400 bg-gray-50 px-2 py-0.5 rounded-full">{{ $contact->hotel->name }}</span>
                    @if (! $contact->email_sent)
                        <span class="text-[0.65rem] bg-red-50 text-red-500 border border-red-100 px-1.5 py-0.5 rounded-full font-semibold">
                            Email no enviado al hotel
                        </span>
                    @else
                        <span class="text-[0.65rem] bg-green-50 text-green-600 border border-green-100 px-1.5 py-0.5 rounded-full font-semibold">
                            Email enviado
                        </span>
                    @endif
                </div>

                <div class="flex flex-wrap gap-x-4 gap-y-0.5 text-sm text-gray-500 mb-2">
                    <a href="mailto:{{ $contact->sender_email }}"
                       class="hover:text-[#2D6A4F] transition flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $contact->sender_email }}
                    </a>
                    @if ($contact->sender_phone)
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $contact->sender_phone }}
                    </span>
                    @endif
                </div>

                {{-- Message preview --}}
                <p class="text-sm text-gray-600 line-clamp-2">{{ $contact->message }}</p>

                {{-- Expand button --}}
                @if (strlen($contact->message) > 120)
                <button onclick="toggleMessage(this)"
                    data-full="{{ e($contact->message) }}"
                    data-preview="{{ e(Str::limit($contact->message, 120)) }}"
                    class="text-xs text-[#2D6A4F] hover:underline font-semibold mt-1">
                    Ver mensaje completo
                </button>
                @endif
            </div>

            {{-- Date --}}
            <div class="text-right flex-shrink-0">
                <p class="text-xs text-gray-400">{{ $contact->created_at->format('d/m/Y') }}</p>
                <p class="text-xs text-gray-300">{{ $contact->created_at->format('H:i') }}</p>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-gray-100 py-16 text-center">
        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <p class="text-gray-400 font-medium">No hay contactos todavía.</p>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if ($contacts->hasPages())
<div class="mt-6">{{ $contacts->links() }}</div>
@endif

<script>
function toggleMessage(btn) {
    const preview = btn.dataset.preview;
    const full    = btn.dataset.full;
    const p       = btn.previousElementSibling;

    if (btn.textContent.trim() === 'Ver mensaje completo') {
        p.textContent = full;
        btn.textContent = 'Ver menos';
    } else {
        p.textContent = preview;
        btn.textContent = 'Ver mensaje completo';
    }
}
</script>

@endsection
