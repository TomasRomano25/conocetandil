@extends('layouts.admin')

@section('title', 'Formularios')
@section('header', 'Formularios')

@section('content')
<div class="space-y-6">

    @foreach ($forms as $form)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-[#1A1A1A]">{{ $form->name }}</h2>
                    <p class="text-xs text-gray-400">slug: <code class="bg-gray-100 px-1 rounded">{{ $form->slug }}</code>
                        · {{ $form->messages_count }} mensaje{{ $form->messages_count !== 1 ? 's' : '' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold {{ $form->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }} px-2.5 py-1 rounded-full">
                    {{ $form->active ? 'Activo' : 'Inactivo' }}
                </span>
                <a href="{{ route('admin.formularios.campos', $form) }}"
                    class="inline-flex items-center gap-1.5 bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold text-xs px-4 py-2 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    Editar campos
                </a>
            </div>
        </div>

        {{-- Settings form --}}
        <form method="POST" action="{{ route('admin.formularios.update', $form) }}" class="px-6 py-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nombre del formulario</label>
                    <input type="text" name="name" value="{{ old('name', $form->name) }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email de notificación</label>
                    <input type="email" name="notification_email" value="{{ old('notification_email', $form->notification_email) }}"
                        placeholder="Dejar vacío para usar el SMTP por defecto"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Descripción</label>
                    <input type="text" name="description" value="{{ old('description', $form->description) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
            </div>

            <div class="flex items-center gap-6 mt-5">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" value="1" class="rounded border-gray-300 text-[#2D6A4F]"
                        {{ $form->active ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-gray-700">Formulario activo</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="send_notification" value="0">
                    <input type="checkbox" name="send_notification" value="1" class="rounded border-gray-300 text-[#2D6A4F]"
                        {{ $form->send_notification ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-gray-700">Enviar notificación por email</span>
                </label>
            </div>

            <div class="flex justify-end mt-5">
                <button type="submit"
                    class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                    Guardar
                </button>
            </div>
        </form>
    </div>
    @endforeach

</div>
@endsection
