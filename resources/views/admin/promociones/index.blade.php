@extends('layouts.admin')
@section('title', 'Promociones')
@section('header', 'Promociones')

@section('content')

{{-- Flash --}}
@if (session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
@endif

{{-- Stats bar --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Códigos activos</p>
        <p class="text-2xl font-bold text-[#2D6A4F]">{{ $activeCount }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Usos totales</p>
        <p class="text-2xl font-bold text-[#1A1A1A]">{{ $totalUses }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Descuento otorgado</p>
        <p class="text-2xl font-bold text-[#1A1A1A]">${{ number_format($totalDiscount, 0, ',', '.') }}</p>
    </div>
</div>

{{-- Create form --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <h2 class="text-base font-bold text-[#1A1A1A] mb-5">Nueva promoción</h2>
    <form method="POST" action="{{ route('admin.promociones.store') }}">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Código *</label>
                <input type="text" name="code" value="{{ old('code') }}" required
                    placeholder="DESCUENTO20"
                    oninput="this.value=this.value.toUpperCase()"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm font-mono uppercase focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre interno *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    placeholder="Ej: Black Friday 2026"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo de descuento *</label>
                <select name="discount_type" id="create-discount-type" onchange="toggleMaxDiscount('create')"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                    <option value="fixed"      @selected(old('discount_type') === 'fixed')>Monto fijo ($)</option>
                    <option value="percentage" @selected(old('discount_type') === 'percentage')>Porcentaje (%)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Valor del descuento *</label>
                <input type="number" name="discount_value" value="{{ old('discount_value') }}" required
                    min="0" step="0.01" placeholder="500"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('discount_value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div id="create-max-discount-wrap" class="{{ old('discount_type') !== 'percentage' ? 'hidden' : '' }}">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Descuento máximo ($)</label>
                <input type="number" name="max_discount" value="{{ old('max_discount') }}"
                    min="0" step="0.01" placeholder="Sin límite"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Aplica a</label>
                <select name="applies_to" id="create-applies-to" onchange="togglePlanSelect('create')"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                    <option value="all"        @selected(old('applies_to') === 'all')>Todos los planes</option>
                    <option value="membership" @selected(old('applies_to') === 'membership')>Planes Premium</option>
                    <option value="hotel"      @selected(old('applies_to') === 'hotel')>Planes de Hotel</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Monto mínimo ($)</label>
                <input type="number" name="min_amount" value="{{ old('min_amount') }}"
                    min="0" step="0.01" placeholder="Sin mínimo"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Usos máximos</label>
                <input type="number" name="max_uses" value="{{ old('max_uses') }}"
                    min="1" placeholder="Ilimitado"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Usos por usuario</label>
                <input type="number" name="max_uses_per_user" value="{{ old('max_uses_per_user', 1) }}"
                    min="1"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Válido desde</label>
                <input type="datetime-local" name="valid_from" value="{{ old('valid_from') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Válido hasta</label>
                <input type="datetime-local" name="valid_until" value="{{ old('valid_until') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>

            <div class="flex items-center gap-2 pt-6">
                <input type="checkbox" name="is_active" id="create_is_active" value="1"
                    {{ old('is_active', '1') ? 'checked' : '' }}
                    class="w-4 h-4 text-[#2D6A4F] rounded border-gray-300 focus:ring-[#52B788]">
                <label for="create_is_active" class="text-sm font-semibold text-gray-700">Activo</label>
            </div>

        </div>

        <div class="flex justify-end mt-5">
            <button type="submit"
                class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                Crear promoción
            </button>
        </div>
    </form>
</div>

{{-- Promotions table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-base font-bold text-[#1A1A1A]">Todas las promociones</h2>
    </div>

    @if ($promotions->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <p>No hay promociones aún. Creá una arriba.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Descuento</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aplica a</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Usos</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Vigencia</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($promotions as $promo)
                    <tr class="hover:bg-gray-50 transition" id="promo-row-{{ $promo->id }}">
                        {{-- View mode --}}
                        <td class="px-4 py-3 promo-view-{{ $promo->id }}">
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-[#2D6A4F] bg-[#2D6A4F]/5 px-2 py-0.5 rounded text-xs">{{ $promo->code }}</span>
                                <button onclick="copyCode('{{ $promo->code }}')" class="text-gray-300 hover:text-gray-500 transition" title="Copiar">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                            </div>
                        </td>
                        <td class="px-4 py-3 font-medium text-[#1A1A1A] promo-view-{{ $promo->id }}">{{ $promo->name }}</td>
                        <td class="px-4 py-3 promo-view-{{ $promo->id }}">
                            @if ($promo->discount_type === 'percentage')
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $promo->discount_value }}%</span>
                                @if ($promo->max_discount)
                                    <span class="text-xs text-gray-400 ml-1">máx ${{ number_format($promo->max_discount, 0, ',', '.') }}</span>
                                @endif
                            @else
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">${{ number_format($promo->discount_value, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 promo-view-{{ $promo->id }}">
                            <span class="text-xs text-gray-600 capitalize">
                                {{ match($promo->applies_to) { 'all' => 'Todos', 'membership' => 'Premium', 'hotel' => 'Hotel', default => $promo->applies_to } }}
                            </span>
                        </td>
                        <td class="px-4 py-3 promo-view-{{ $promo->id }}">
                            <span class="text-xs text-gray-700 font-semibold">{{ $promo->uses_count }}</span>
                            @if ($promo->max_uses)
                                <span class="text-xs text-gray-400"> / {{ $promo->max_uses }}</span>
                            @else
                                <span class="text-xs text-gray-400"> / ∞</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 promo-view-{{ $promo->id }}">
                            <div class="text-xs text-gray-500">
                                @if ($promo->valid_from)
                                    <span>Desde {{ $promo->valid_from->format('d/m/Y') }}</span><br>
                                @endif
                                @if ($promo->valid_until)
                                    <span>Hasta {{ $promo->valid_until->format('d/m/Y') }}</span>
                                @else
                                    <span class="text-gray-400">Sin vencimiento</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 promo-view-{{ $promo->id }}">
                            @if ($promo->is_active && $promo->isValid())
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-100 px-2 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Activo
                                </span>
                            @elseif ($promo->is_active)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full">
                                    Vencido
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                    Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 promo-view-{{ $promo->id }}">
                            <div class="flex items-center gap-2">
                                <button onclick="editPromo({{ $promo->id }})"
                                    class="text-xs text-[#2D6A4F] hover:text-[#1A1A1A] font-semibold transition">
                                    Editar
                                </button>
                                <form method="POST" action="{{ route('admin.promociones.destroy', ['promo' => $promo->id]) }}"
                                    onsubmit="return confirm('¿Eliminar o desactivar esta promoción?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-xs text-red-500 hover:text-red-700 font-semibold transition">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit row (hidden by default) --}}
                    <tr id="promo-edit-{{ $promo->id }}" class="hidden bg-blue-50 border-b border-blue-100">
                        <td colspan="8" class="px-4 py-4">
                            <form method="POST" action="{{ route('admin.promociones.update', ['promo' => $promo->id]) }}">
                                @csrf @method('PUT')
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mb-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nombre</label>
                                        <input type="text" name="name" value="{{ $promo->name }}" required
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tipo</label>
                                        <select name="discount_type" id="edit-discount-type-{{ $promo->id }}"
                                            onchange="toggleMaxDiscount('edit-{{ $promo->id }}')"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                                            <option value="fixed"      @selected($promo->discount_type === 'fixed')>Fijo ($)</option>
                                            <option value="percentage" @selected($promo->discount_type === 'percentage')>% Porcentaje</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Valor</label>
                                        <input type="number" name="discount_value" value="{{ $promo->discount_value }}" required
                                            min="0" step="0.01"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                                    </div>
                                    <div id="edit-{{ $promo->id }}-max-discount-wrap" class="{{ $promo->discount_type !== 'percentage' ? 'hidden' : '' }}">
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Máx. descuento ($)</label>
                                        <input type="number" name="max_discount" value="{{ $promo->max_discount }}"
                                            min="0" step="0.01" placeholder="Sin límite"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Aplica a</label>
                                        <select name="applies_to"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                                            <option value="all"        @selected($promo->applies_to === 'all')>Todos</option>
                                            <option value="membership" @selected($promo->applies_to === 'membership')>Premium</option>
                                            <option value="hotel"      @selected($promo->applies_to === 'hotel')>Hotel</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Usos máximos</label>
                                        <input type="number" name="max_uses" value="{{ $promo->max_uses }}" min="1" placeholder="∞"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Usos / usuario</label>
                                        <input type="number" name="max_uses_per_user" value="{{ $promo->max_uses_per_user }}" min="1"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Válido desde</label>
                                        <input type="datetime-local" name="valid_from"
                                            value="{{ $promo->valid_from?->format('Y-m-d\TH:i') }}"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Válido hasta</label>
                                        <input type="datetime-local" name="valid_until"
                                            value="{{ $promo->valid_until?->format('Y-m-d\TH:i') }}"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                                    </div>
                                    <div class="flex items-end pb-0.5">
                                        <label class="flex items-center gap-2 text-sm">
                                            <input type="checkbox" name="is_active" value="1"
                                                {{ $promo->is_active ? 'checked' : '' }}
                                                class="w-4 h-4 text-[#2D6A4F] rounded border-gray-300 focus:ring-[#52B788]">
                                            <span class="font-semibold text-gray-700">Activo</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit"
                                        class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-5 rounded-lg transition text-xs">
                                        Guardar
                                    </button>
                                    <button type="button" onclick="cancelEdit({{ $promo->id }})"
                                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-5 rounded-lg transition text-xs">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script>
function editPromo(id) {
    document.getElementById('promo-edit-' + id).classList.remove('hidden');
    document.querySelectorAll('.promo-view-' + id).forEach(el => el.classList.add('hidden'));
}

function cancelEdit(id) {
    document.getElementById('promo-edit-' + id).classList.add('hidden');
    document.querySelectorAll('.promo-view-' + id).forEach(el => el.classList.remove('hidden'));
}

function toggleMaxDiscount(prefix) {
    var type = document.getElementById(prefix + '-discount-type')?.value
        || document.getElementById('create-discount-type')?.value;
    if (prefix === 'create') {
        var wrap = document.getElementById('create-max-discount-wrap');
        wrap.classList.toggle('hidden', type !== 'percentage');
    } else {
        var wrapId = prefix + '-max-discount-wrap';
        var wrap = document.getElementById(wrapId);
        if (wrap) wrap.classList.toggle('hidden', type !== 'percentage');
    }
}

function copyCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        var el = document.createElement('div');
        el.textContent = '✓ Copiado: ' + code;
        el.className = 'fixed top-4 right-4 bg-[#2D6A4F] text-white text-sm font-semibold px-4 py-2 rounded-xl shadow-lg z-50';
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 2000);
    });
}
</script>

@endsection
