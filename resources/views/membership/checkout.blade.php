@extends('layouts.app')
@section('title', 'Checkout — ' . $plan->name)

@section('content')

<section class="min-h-screen bg-gray-50 py-12">
<div class="max-w-3xl mx-auto px-4 sm:px-6">

    <div class="mb-6">
        <a href="{{ route('membership.planes') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#2D6A4F] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a los planes
        </a>
    </div>

    <h1 class="text-2xl font-bold text-[#1A1A1A] mb-8">Finalizar compra</h1>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

        {{-- Left: Bank transfer instructions --}}
        <div class="md:col-span-3 space-y-5">

            {{-- Transfer details --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-bold text-[#1A1A1A] mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-[#2D6A4F] text-white rounded-full flex items-center justify-center text-xs font-bold">1</span>
                    Realizá la transferencia
                </h2>

                @if ($bankConfig['cbu'] || $bankConfig['alias'])
                <div class="space-y-3">
                    @if ($bankConfig['bank_name'])
                    <div class="flex justify-between text-sm py-2 border-b border-gray-50">
                        <span class="text-gray-500">Banco</span>
                        <span class="font-semibold text-[#1A1A1A]">{{ $bankConfig['bank_name'] }}</span>
                    </div>
                    @endif
                    @if ($bankConfig['account_holder'])
                    <div class="flex justify-between text-sm py-2 border-b border-gray-50">
                        <span class="text-gray-500">Titular</span>
                        <span class="font-semibold text-[#1A1A1A]">{{ $bankConfig['account_holder'] }}</span>
                    </div>
                    @endif
                    @if ($bankConfig['cbu'])
                    <div class="flex justify-between items-center text-sm py-2 border-b border-gray-50">
                        <span class="text-gray-500">CBU</span>
                        <button onclick="copyText('{{ $bankConfig['cbu'] }}', this)"
                            class="font-mono font-semibold text-[#2D6A4F] hover:underline text-right">
                            {{ $bankConfig['cbu'] }}
                        </button>
                    </div>
                    @endif
                    @if ($bankConfig['alias'])
                    <div class="flex justify-between items-center text-sm py-2 border-b border-gray-50">
                        <span class="text-gray-500">Alias</span>
                        <button onclick="copyText('{{ $bankConfig['alias'] }}', this)"
                            class="font-semibold text-[#2D6A4F] hover:underline">
                            {{ $bankConfig['alias'] }}
                        </button>
                    </div>
                    @endif
                    @if ($bankConfig['account_number'])
                    <div class="flex justify-between text-sm py-2">
                        <span class="text-gray-500">N° de cuenta</span>
                        <span class="font-semibold text-[#1A1A1A]">{{ $bankConfig['account_number'] }}</span>
                    </div>
                    @endif
                </div>

                @if ($bankConfig['instructions'])
                <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800">
                    {{ $bankConfig['instructions'] }}
                </div>
                @endif

                @else
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800">
                    Los datos bancarios serán publicados próximamente. Contactanos para concretar el pago.
                </div>
                @endif
            </div>

            {{-- Transfer amount reminder --}}
            <div class="bg-[#2D6A4F]/5 border border-[#2D6A4F]/20 rounded-2xl p-5">
                <p class="text-sm text-[#2D6A4F] font-semibold mb-1">Monto a transferir</p>
                <p class="text-3xl font-bold text-[#2D6A4F]">{{ $plan->formattedPrice() }}</p>
                <p class="text-xs text-gray-500 mt-1">Plan {{ $plan->name }} · {{ $plan->durationLabel() }}</p>
            </div>

            {{-- Submit form --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-bold text-[#1A1A1A] mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-[#2D6A4F] text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                    Confirmá tu pedido
                </h2>
                <p class="text-sm text-gray-500 mb-5">
                    Podés incluir el número de comprobante de la transferencia para que podamos verificarla más rápido.
                </p>

                <form method="POST" action="{{ route('membership.store', $plan->slug) }}">
                    @csrf
                    <div class="mb-5">
                        <label for="transfer_reference" class="block text-sm font-medium text-gray-700 mb-1">
                            Número de comprobante <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <input type="text" name="transfer_reference" id="transfer_reference"
                            value="{{ old('transfer_reference') }}"
                            placeholder="Ej: 00123456789"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent">
                    </div>

                    <button type="submit"
                        class="w-full bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-bold py-4 rounded-xl transition text-base">
                        Confirmar pedido →
                    </button>
                    <p class="text-xs text-gray-400 text-center mt-3">
                        Al confirmar, aceptás que revisaremos la transferencia y activaremos tu cuenta en menos de 24 hs.
                    </p>
                </form>
            </div>
        </div>

        {{-- Right: Order summary --}}
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Resumen</p>

                <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-100">
                    <div>
                        <p class="font-bold text-[#1A1A1A]">{{ $plan->name }}</p>
                        <p class="text-xs text-gray-400">{{ $plan->durationLabel() }} de acceso Premium</p>
                    </div>
                    <span class="font-bold text-[#2D6A4F]">{{ $plan->formattedPrice() }}</span>
                </div>

                <div class="flex justify-between text-sm font-bold text-[#1A1A1A] pt-1">
                    <span>Total</span>
                    <span>{{ $plan->formattedPrice() }}</span>
                </div>

                <div class="mt-5 pt-4 border-t border-gray-100 space-y-2">
                    @foreach ($plan->features ?? [] as $feature)
                    <div class="flex items-start gap-2 text-xs text-gray-500">
                        <svg class="w-3.5 h-3.5 text-[#2D6A4F] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $feature }}
                    </div>
                    @endforeach
                </div>

                <div class="mt-5 pt-4 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-400">Cuenta: <strong>{{ auth()->user()->email }}</strong></p>
                </div>
            </div>
        </div>
    </div>

</div>
</section>

<script>
function copyText(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const orig = btn.textContent.trim();
        btn.textContent = '✓ Copiado';
        setTimeout(() => { btn.textContent = orig; }, 2000);
    });
}
</script>

@endsection
