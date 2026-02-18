@extends('layouts.admin')

@section('title', 'Editar Usuario')
@section('header', 'Editar Usuario')

@section('content')
    <div class="max-w-2xl space-y-6">
        <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $usuario->name) }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <input type="password" name="password" id="password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                <p class="text-xs text-gray-500 mt-1">Dejá en blanco para mantener la contraseña actual.</p>
                @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_admin" value="1" {{ old('is_admin', $usuario->is_admin) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-[#2D6A4F] focus:ring-[#52B788]">
                    <span class="ml-2 text-sm text-gray-700">Administrador</span>
                </label>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t">
                <button type="submit" class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-6 rounded-lg transition">
                    Guardar Cambios
                </button>
                <a href="{{ route('admin.usuarios.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">Cancelar</a>
            </div>
        </form>
        {{-- Premium Management --}}
        @unless ($usuario->is_admin)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-[#1A1A1A]">Acceso Premium</h3>
                    <p class="text-sm text-gray-500 mt-0.5">
                        @if ($usuario->isPremium())
                            <span class="text-amber-600 font-semibold">✦ Activo</span>
                            — vence el {{ $usuario->premium_expires_at->format('d/m/Y H:i') }}
                            ({{ $usuario->premium_expires_at->diffForHumans() }})
                        @else
                            Sin acceso Premium activo.
                        @endif
                    </p>
                </div>
            </div>

            {{-- Grant form --}}
            <form method="POST" action="{{ route('admin.usuarios.premium.grant', $usuario) }}" class="flex flex-wrap items-end gap-3">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Duración</label>
                    <select name="duration" id="duration-select"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white"
                        onchange="document.getElementById('custom-date').classList.toggle('hidden', this.value !== 'custom')">
                        <option value="1month">1 mes</option>
                        <option value="3months">3 meses</option>
                        <option value="6months">6 meses</option>
                        <option value="1year">1 año</option>
                        <option value="custom">Fecha personalizada</option>
                    </select>
                </div>
                <div id="custom-date" class="hidden">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Vence el</label>
                    <input type="date" name="expires_at"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
                <button type="submit"
                    class="bg-amber-500 hover:bg-amber-600 text-white font-semibold py-2 px-5 rounded-lg transition text-sm">
                    {{ $usuario->isPremium() ? 'Extender Premium' : 'Otorgar Premium' }}
                </button>
            </form>

            @if ($usuario->isPremium())
            <form method="POST" action="{{ route('admin.usuarios.premium.revoke', $usuario) }}"
                onsubmit="return confirm('¿Revocar el acceso Premium de este usuario?')">
                @csrf
                <button type="submit"
                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                    Revocar acceso Premium
                </button>
            </form>
            @endif
        </div>
        @endunless

    </div>
@endsection
