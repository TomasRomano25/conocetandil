@extends('layouts.app')
@section('title', 'Checkout — ' . $order->hotel->name)

@section('content')

<section class="min-h-screen bg-gray-50 py-12">
<div class="max-w-3xl mx-auto px-4 sm:px-6">

    <div class="mb-6">
        <a href="{{ route('hoteles.owner.panel') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#2D6A4F] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver al panel
        </a>
    </div>

    <h1 class="text-2xl font-bold text-[#1A1A1A] mb-8">Finalizar registro del hotel</h1>

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
                @if ($order->plan->hasSale())
                    <div class="flex items-baseline gap-2">
                        <s class="text-gray-400 text-lg">{{ $order->plan->formattedPrice() }}</s>
                        <span class="text-3xl font-bold text-[#2D6A4F]" id="checkout-total-display">{{ $order->plan->formattedEffectivePrice() }}</span>
                        @if ($order->plan->sale_label)
                            <span class="bg-amber-100 text-amber-700 text-xs rounded-full px-2 py-0.5 font-semibold">{{ $order->plan->sale_label }}</span>
                        @endif
                    </div>
                @else
                    <p class="text-3xl font-bold text-[#2D6A4F]" id="checkout-total-display">{{ $order->plan->formattedEffectivePrice() }}</p>
                @endif
                <p class="text-xs text-gray-500 mt-1">Plan {{ $order->plan->name }} · {{ $order->plan->durationLabel() }}</p>
            </div>

            {{-- Submit form --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-bold text-[#1A1A1A] mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-[#2D6A4F] text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                    Confirmá tu pedido
                </h2>
                <p class="text-sm text-gray-500 mb-5">
                    Una vez verificada la transferencia activaremos tu hotel en el directorio.
                </p>

                <form id="hotel-checkout-form" method="POST" action="{{ route('hoteles.owner.storeCheckout', $order) }}">
                    @csrf
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-hotel-checkout">
                    <input type="hidden" name="promotion_id" id="hotel-promotion-id-input">

                    {{-- Coupon code --}}
                    <div class="mb-5 border border-gray-200 rounded-xl p-4">
                        <p class="text-sm font-semibold text-gray-700 mb-2">¿Tenés un código de descuento?</p>
                        <div class="flex gap-2">
                            <input type="text" id="hotel-coupon-code"
                                placeholder="CODIGO"
                                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                            <button type="button" onclick="validateHotelCoupon()"
                                class="bg-[#2D6A4F] text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-[#1A1A1A] transition">
                                Aplicar
                            </button>
                        </div>
                        <div id="hotel-coupon-result" class="hidden mt-2 text-sm"></div>
                    </div>

                    <div class="mb-5">
                        <label for="transfer_reference" class="block text-sm font-medium text-gray-700 mb-1">
                            Número de comprobante <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <input type="text" name="transfer_reference" id="transfer_reference"
                            value="{{ old('transfer_reference') }}"
                            placeholder="Ej: 00123456789"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent">
                    </div>

                    @error('captcha')
                        <p class="text-red-500 text-xs mb-3">{{ $message }}</p>
                    @enderror

                    <button type="submit"
                        class="w-full bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-bold py-4 rounded-xl transition text-base">
                        Confirmar pedido →
                    </button>
                    <p class="text-xs text-gray-400 text-center mt-3">
                        Al confirmar, revisaremos la transferencia y activaremos tu hotel en el directorio.
                    </p>
                </form>
            </div>
        </div>

        {{-- Right: Order summary --}}
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Resumen</p>

                <div class="mb-3 pb-3 border-b border-gray-100">
                    <p class="font-bold text-[#1A1A1A]">{{ $order->hotel->name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $order->plan->name }} · {{ $order->plan->durationLabel() }}</p>
                </div>

                <div class="flex justify-between text-sm py-1.5">
                    <span class="text-gray-500">Precio base</span>
                    <span class="font-semibold text-[#1A1A1A]">{{ $order->plan->formattedEffectivePrice() }}</span>
                </div>

                <div id="hotel-discount-row" class="hidden flex justify-between text-sm py-1.5 text-green-600">
                    <span>Descuento</span>
                    <span id="hotel-discount-display">-$0</span>
                </div>

                <div class="flex justify-between text-sm font-bold text-[#1A1A1A] pt-2 border-t border-gray-100 mt-2">
                    <span>Total</span>
                    <span id="hotel-total-display">{{ $order->plan->formattedEffectivePrice() }}</span>
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

var hotelBaseAmount = {{ $order->amount }};
var hotelAppliedDiscount = 0;

function validateHotelCoupon() {
    var code = document.getElementById('hotel-coupon-code').value.trim();
    if (!code) return;
    var result = document.getElementById('hotel-coupon-result');
    result.className = 'mt-2 text-sm text-gray-500';
    result.textContent = 'Validando...';
    result.classList.remove('hidden');

    fetch('/api/validate-coupon?code=' + encodeURIComponent(code) + '&type=hotel&plan_id={{ $order->plan_id }}&amount=' + hotelBaseAmount)
        .then(r => r.json())
        .then(data => {
            if (data.valid) {
                hotelAppliedDiscount = data.discount;
                document.getElementById('hotel-promotion-id-input').value = data.promotion_id;
                result.className = 'mt-2 text-sm text-green-600 font-semibold';
                result.textContent = '✓ ' + data.message;
                document.getElementById('hotel-discount-row').classList.remove('hidden');
                document.getElementById('hotel-discount-display').textContent = '-' + data.discount_formatted;
                var total = hotelBaseAmount - hotelAppliedDiscount;
                document.getElementById('hotel-total-display').textContent = '$' + total.toLocaleString('es-AR');
                document.getElementById('checkout-total-display').textContent = '$' + total.toLocaleString('es-AR');
            } else {
                hotelAppliedDiscount = 0;
                document.getElementById('hotel-promotion-id-input').value = '';
                result.className = 'mt-2 text-sm text-red-600';
                result.textContent = data.message;
                document.getElementById('hotel-discount-row').classList.add('hidden');
                document.getElementById('hotel-total-display').textContent = '$' + hotelBaseAmount.toLocaleString('es-AR');
                document.getElementById('checkout-total-display').textContent = '$' + hotelBaseAmount.toLocaleString('es-AR');
            }
        });
}

@if(\App\Models\Configuration::get('recaptcha_site_key'))
document.getElementById('hotel-checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = this;
    grecaptcha.ready(function() {
        grecaptcha.execute('{{ \App\Models\Configuration::get("recaptcha_site_key") }}', {action: 'hotel_checkout'}).then(function(token) {
            document.getElementById('g-recaptcha-hotel-checkout').value = token;
            form.submit();
        });
    });
});
@endif
</script>

@endsection
