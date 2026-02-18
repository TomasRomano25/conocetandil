@extends('layouts.admin')

@section('title', 'Mensajes')
@section('header', 'Mensajes')

@section('content')
<div class="space-y-6">

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4">
        <form method="GET" action="{{ route('admin.mensajes.index') }}" class="flex flex-wrap gap-4 items-end">

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Formulario</label>
                <select name="form_id"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                    <option value="">Todos los formularios</option>
                    @foreach ($forms as $f)
                        <option value="{{ $f->id }}" @selected($formId == $f->id)>{{ $f->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Estado</label>
                <select name="is_read"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                    <option value="">Todos</option>
                    <option value="0" @selected($isRead === '0')>No leídos</option>
                    <option value="1" @selected($isRead === '1')>Leídos</option>
                </select>
            </div>

            <button type="submit"
                class="bg-[#2D6A4F] text-white font-semibold px-4 py-2 rounded-lg text-sm hover:bg-[#1A1A1A] transition">
                Filtrar
            </button>

            @if ($formId || $isRead !== null && $isRead !== '')
                <a href="{{ route('admin.mensajes.index') }}"
                    class="text-sm text-gray-500 hover:text-gray-800 py-2">
                    Limpiar filtros
                </a>
            @endif

            @if ($unreadCount > 0)
                <span class="ml-auto inline-flex items-center gap-1.5 bg-red-100 text-red-700 text-xs font-bold px-3 py-1.5 rounded-full">
                    <span class="w-2 h-2 bg-red-500 rounded-full inline-block"></span>
                    {{ $unreadCount }} sin leer
                </span>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if ($messages->isEmpty())
            <div class="px-6 py-16 text-center text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <p class="font-medium">No hay mensajes</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        <th class="px-6 py-3 text-left w-4"></th>
                        <th class="px-4 py-3 text-left">Formulario</th>
                        <th class="px-4 py-3 text-left">Remitente</th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">Fecha</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($messages as $msg)
                        <tr class="{{ $msg->is_read ? 'bg-white' : 'bg-blue-50/30' }} hover:bg-gray-50 transition">
                            {{-- Unread dot --}}
                            <td class="pl-6 pr-2 py-4">
                                @if (! $msg->is_read)
                                    <span class="w-2 h-2 bg-blue-500 rounded-full inline-block"></span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center bg-[#2D6A4F]/10 text-[#2D6A4F] text-xs font-semibold px-2.5 py-1 rounded-full">
                                    {{ $msg->form->name ?? '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 font-medium text-[#1A1A1A]">
                                {{ $msg->getValue('nombre') ?? $msg->getValue('name') ?? '—' }}
                                @if ($email = $msg->getValue('email'))
                                    <span class="block text-xs text-gray-400 font-normal">{{ $email }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-gray-500 hidden md:table-cell">
                                {{ $msg->created_at->format('d/m/Y H:i') }}
                                <span class="block text-xs text-gray-400">{{ $msg->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('admin.mensajes.show', $msg) }}"
                                    class="inline-flex items-center gap-1 text-[#2D6A4F] hover:text-[#1A1A1A] font-semibold text-xs border border-[#2D6A4F]/30 hover:border-[#1A1A1A] px-3 py-1.5 rounded-lg transition">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($messages->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $messages->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
