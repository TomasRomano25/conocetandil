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

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700 flex items-start gap-2">
        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">
        @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

        {{-- Left: Payment methods --}}
        <div class="md:col-span-3 space-y-5">

        @php
            $bankTransferEnabled  = \App\Models\Configuration::get('payment_bank_transfer_enabled', '1') === '1';
            $mercadopagoEnabled   = \App\Models\Configuration::get('payment_mercadopago_enabled', '0') === '1';
            $bothEnabled          = $bankTransferEnabled && $mercadopagoEnabled;
        @endphp

        {{-- Payment method tabs (if both enabled) --}}
        @if ($bothEnabled)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <p class="text-sm font-bold text-[#1A1A1A] mb-3">Elegí cómo pagar</p>
            <div class="flex gap-2">
                <button type="button" onclick="switchHotelPayment('transfer')" id="hotel-tab-transfer"
                    class="flex-1 py-2.5 px-4 rounded-xl border-2 border-[#2D6A4F] bg-[#2D6A4F] text-white font-semibold text-sm transition">
                    Transferencia bancaria
                </button>
                <button type="button" onclick="switchHotelPayment('mp')" id="hotel-tab-mp"
                    class="flex-1 py-2.5 px-4 rounded-xl border-2 border-gray-200 text-gray-600 font-semibold text-sm transition hover:border-[#2D6A4F]">
                    MercadoPago
                </button>
            </div>
        </div>
        @endif

            {{-- Transfer details --}}
            <div id="hotel-panel-transfer" class="{{ (!$bankTransferEnabled) ? 'hidden' : '' }} bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
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
            <div id="hotel-transfer-amount-reminder" class="{{ (!$bankTransferEnabled) ? 'hidden' : '' }} bg-[#2D6A4F]/5 border border-[#2D6A4F]/20 rounded-2xl p-5">
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
            <div id="hotel-transfer-form-panel" class="{{ (!$bankTransferEnabled) ? 'hidden' : '' }} bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
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

            {{-- MercadoPago panel --}}
            @if ($mercadopagoEnabled)
            <div id="hotel-panel-mp" class="{{ $bothEnabled ? 'hidden' : '' }} space-y-4">

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    {{-- MP header --}}
                    <div class="bg-[#009EE3] px-6 py-4 flex items-center gap-3">
                        <svg class="w-7 h-7 text-white flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.977 8.138c-.005-.018-.01-.036-.015-.054C22.004 4.461 18.39 2 14.222 2H7.652C6.234 2 5.03 3.02 4.812 4.422L2.014 19.578A1.5 1.5 0 003.5 21.25h4.11l1.032-6.55.032-.193c.218-1.402 1.422-2.422 2.84-2.422h.892c3.59 0 6.401-1.46 7.222-5.683.003-.017.006-.034.008-.05a4.27 4.27 0 00.07-.562c.024-.3.024-.614-.001-.902z"/>
                        </svg>
                        <div>
                            <p class="font-bold text-white text-sm">Pagar con MercadoPago</p>
                            <p class="text-blue-100 text-xs">Tarjeta, débito o efectivo · Pago seguro</p>
                        </div>
                    </div>

                    <div class="px-6 py-5">
                        {{-- Amount --}}
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 mb-5">
                            <span class="text-sm text-gray-500">Total a pagar</span>
                            <div class="text-right">
                                @if ($order->plan->hasSale())
                                    <s class="text-gray-400 text-sm block">{{ $order->plan->formattedPrice() }}</s>
                                    <span class="text-xl font-bold text-[#2D6A4F]" id="hotel-mp-total-display">{{ $order->plan->formattedEffectivePrice() }}</span>
                                @else
                                    <span class="text-xl font-bold text-[#2D6A4F]" id="hotel-mp-total-display">{{ $order->plan->formattedEffectivePrice() }}</span>
                                @endif
                            </div>
                        </div>

                        <form id="hotel-mp-form" method="POST" action="{{ route('hoteles.owner.storeCheckout', $order) }}">
                            @csrf
                            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-hotel-mp">
                            <input type="hidden" name="promotion_id" id="hotel-mp-promotion-id-input">
                            <input type="hidden" name="redirect_to_mp" value="1">

                            {{-- Coupon --}}
                            <div class="mb-5">
                                <p class="text-sm font-semibold text-gray-700 mb-2">¿Tenés un código de descuento?</p>
                                <div class="flex gap-2">
                                    <input type="text" id="hotel-mp-coupon-code"
                                        placeholder="CODIGO"
                                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                                    <button type="button" onclick="validateHotelMpCoupon()"
                                        class="bg-[#2D6A4F] text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-[#1A1A1A] transition">
                                        Aplicar
                                    </button>
                                </div>
                                <div id="hotel-mp-coupon-result" class="hidden mt-2 text-sm"></div>
                            </div>

                            <button type="submit"
                                class="w-full bg-[#009EE3] hover:bg-[#0088cc] text-white font-bold py-4 rounded-xl transition text-base flex items-center justify-center gap-2">
                                Continuar al pago →
                            </button>
                            <p class="text-xs text-gray-400 text-center mt-2">
                                Serás redirigido al sitio seguro de MercadoPago
                            </p>
                        </form>
                    </div>
                </div>
            </div>
            @endif

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

                <div id="hotel-discount-row" class="hidden justify-between text-sm py-1.5 text-green-600">
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
@if ($bothEnabled ?? false)
document.addEventListener('DOMContentLoaded', function() {
    @if(session('open_mp_tab'))
    switchHotelPayment('mp');
    @endif
});

function switchHotelPayment(mode) {
    var transferPanels = ['hotel-panel-transfer', 'hotel-transfer-amount-reminder', 'hotel-transfer-form-panel'];
    var mpPanels = ['hotel-panel-mp'];

    if (mode === 'transfer') {
        transferPanels.forEach(id => { var el = document.getElementById(id); if(el) el.classList.remove('hidden'); });
        mpPanels.forEach(id => { var el = document.getElementById(id); if(el) el.classList.add('hidden'); });
        document.getElementById('hotel-tab-transfer').className = 'flex-1 py-2.5 px-4 rounded-xl border-2 border-[#2D6A4F] bg-[#2D6A4F] text-white font-semibold text-sm transition';
        document.getElementById('hotel-tab-mp').className = 'flex-1 py-2.5 px-4 rounded-xl border-2 border-gray-200 text-gray-600 font-semibold text-sm transition hover:border-[#2D6A4F]';
    } else {
        transferPanels.forEach(id => { var el = document.getElementById(id); if(el) el.classList.add('hidden'); });
        mpPanels.forEach(id => { var el = document.getElementById(id); if(el) el.classList.remove('hidden'); });
        document.getElementById('hotel-tab-mp').className = 'flex-1 py-2.5 px-4 rounded-xl border-2 border-[#2D6A4F] bg-[#2D6A4F] text-white font-semibold text-sm transition';
        document.getElementById('hotel-tab-transfer').className = 'flex-1 py-2.5 px-4 rounded-xl border-2 border-gray-200 text-gray-600 font-semibold text-sm transition hover:border-[#2D6A4F]';
    }
}
@endif

var hotelMpAppliedDiscount = 0;
function validateHotelMpCoupon() {
    var code = document.getElementById('hotel-mp-coupon-code').value.trim();
    if (!code) return;
    var result = document.getElementById('hotel-mp-coupon-result');
    result.className = 'mt-2 text-sm text-gray-500';
    result.textContent = 'Validando...';
    result.classList.remove('hidden');

    fetch('/api/validate-coupon?code=' + encodeURIComponent(code) + '&type=hotel&plan_id={{ $order->plan_id }}&amount=' + hotelBaseAmount)
        .then(r => r.json())
        .then(data => {
            if (data.valid) {
                hotelMpAppliedDiscount = data.discount;
                document.getElementById('hotel-mp-promotion-id-input').value = data.promotion_id;
                result.className = 'mt-2 text-sm text-green-600 font-semibold';
                result.textContent = '✓ ' + data.message;
                var total = hotelBaseAmount - data.discount;
                document.getElementById('hotel-mp-total-display').textContent = '$' + total.toLocaleString('es-AR');
            } else {
                hotelMpAppliedDiscount = 0;
                document.getElementById('hotel-mp-promotion-id-input').value = '';
                result.className = 'mt-2 text-sm text-red-600';
                result.textContent = data.message;
                document.getElementById('hotel-mp-total-display').textContent = '$' + hotelBaseAmount.toLocaleString('es-AR');
            }
        });
}

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
                var hdr = document.getElementById('hotel-discount-row');
                hdr.classList.remove('hidden'); hdr.classList.add('flex');
                document.getElementById('hotel-discount-display').textContent = '-' + data.discount_formatted;
                var total = hotelBaseAmount - hotelAppliedDiscount;
                document.getElementById('hotel-total-display').textContent = '$' + total.toLocaleString('es-AR');
                document.getElementById('checkout-total-display').textContent = '$' + total.toLocaleString('es-AR');
            } else {
                hotelAppliedDiscount = 0;
                document.getElementById('hotel-promotion-id-input').value = '';
                result.className = 'mt-2 text-sm text-red-600';
                result.textContent = data.message;
                var hdr2 = document.getElementById('hotel-discount-row');
                hdr2.classList.add('hidden'); hdr2.classList.remove('flex');
                document.getElementById('hotel-total-display').textContent = '$' + hotelBaseAmount.toLocaleString('es-AR');
                document.getElementById('checkout-total-display').textContent = '$' + hotelBaseAmount.toLocaleString('es-AR');
            }
        });
}

@if(\App\Models\Configuration::get('recaptcha_site_key'))
[['hotel-checkout-form', 'g-recaptcha-hotel-checkout'], ['hotel-mp-form', 'g-recaptcha-hotel-mp']].forEach(function(pair) {
    var form = document.getElementById(pair[0]);
    if (!form) return;
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var f = this;
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ \App\Models\Configuration::get("recaptcha_site_key") }}', {action: 'hotel_checkout'}).then(function(token) {
                document.getElementById(pair[1]).value = token;
                f.submit();
            });
        });
    });
});
@endif
</script>

@endsection
