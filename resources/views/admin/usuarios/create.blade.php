@extends('layouts.admin')

@section('title', 'Crear Usuario')
@section('header', 'Crear Usuario')

@section('content')
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.usuarios.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña *</label>
                <input type="password" name="password" id="password" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña *</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}
                        class="rounded border-gray-300 text-[#2D6A4F] focus:ring-[#52B788]">
                    <span class="ml-2 text-sm text-gray-700">Administrador</span>
                </label>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t">
                <button type="submit" class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-6 rounded-lg transition">
                    Crear Usuario
                </button>
                <a href="{{ route('admin.usuarios.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
