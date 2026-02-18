@extends('layouts.admin')

@section('title', 'Usuarios')
@section('header', 'Usuarios')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <p class="text-gray-600">Gestioná los usuarios del sistema.</p>
        <a href="{{ route('admin.usuarios.create') }}" class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-4 rounded-lg transition">
            + Nuevo Usuario
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Premium</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($usuarios as $usuario)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-[#1A1A1A]">{{ $usuario->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $usuario->email }}</td>
                        <td class="px-6 py-4">
                            @if ($usuario->is_admin)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Admin</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Usuario</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 hidden md:table-cell">
                            @if ($usuario->is_admin)
                                <span class="text-xs text-gray-400">Admin (siempre)</span>
                            @elseif ($usuario->isPremium())
                                <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    ✦ hasta {{ $usuario->premium_expires_at->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400">Free</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="text-[#2D6A4F] hover:text-[#52B788] font-medium text-sm">Editar</a>
                            @if ($usuario->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $usuarios->links() }}
    </div>
@endsection
