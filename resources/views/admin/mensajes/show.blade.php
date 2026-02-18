@extends('layouts.admin')

@section('title', 'Ver Mensaje')
@section('header', 'Ver Mensaje')

@section('content')
<div class="max-w-2xl space-y-6">

    {{-- Back --}}
    <a href="{{ route('admin.mensajes.index') }}"
        class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#2D6A4F] transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a mensajes
    </a>

    {{-- Message card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <span class="inline-flex items-center bg-[#2D6A4F]/10 text-[#2D6A4F] text-xs font-semibold px-2.5 py-1 rounded-full mb-1">
                    {{ $mensaje->form->name }}
                </span>
                <p class="text-xs text-gray-400">
                    {{ $mensaje->created_at->format('d/m/Y H:i:s') }}
                    ({{ $mensaje->created_at->diffForHumans() }})
                    @if ($mensaje->ip_address)· IP {{ $mensaje->ip_address }}@endif
                </p>
            </div>
            <span class="text-xs font-semibold {{ $mensaje->is_read ? 'text-gray-400' : 'text-blue-600 bg-blue-50 px-2 py-1 rounded-full' }}">
                {{ $mensaje->is_read ? 'Leído' : 'No leído' }}
            </span>
        </div>

        <div class="px-6 py-5 space-y-5">
            @foreach ($mensaje->form->fields as $field)
                @if ($field->visible && isset($mensaje->data[$field->name]))
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">{{ $field->label }}</p>
                    <p class="text-[#1A1A1A] bg-gray-50 rounded-lg px-4 py-3 text-sm whitespace-pre-wrap border-l-4 border-[#52B788]">
                        {{ $mensaje->data[$field->name] }}
                    </p>
                </div>
                @endif
            @endforeach
        </div>

        <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap gap-3">
            @if (! $mensaje->is_read)
                <form method="POST" action="{{ route('admin.mensajes.read', $mensaje) }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-[#2D6A4F] text-white font-semibold text-sm px-4 py-2 rounded-lg hover:bg-[#1A1A1A] transition">
                        Marcar como leído
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.mensajes.unread', $mensaje) }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 border border-gray-300 text-gray-600 font-semibold text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                        Marcar como no leído
                    </button>
                </form>
            @endif

            @if ($email = $mensaje->getValue('email'))
                <a href="mailto:{{ $email }}"
                    class="inline-flex items-center gap-2 border-2 border-[#2D6A4F] text-[#2D6A4F] font-semibold text-sm px-4 py-2 rounded-lg hover:bg-[#2D6A4F] hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Responder por email
                </a>
            @endif

            <form method="POST" action="{{ route('admin.mensajes.destroy', $mensaje) }}"
                onsubmit="return confirm('¿Eliminar este mensaje?')" class="ml-auto">
                @csrf @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-2 text-red-600 hover:text-red-800 font-semibold text-sm px-4 py-2 rounded-lg border border-red-200 hover:border-red-400 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Eliminar
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
