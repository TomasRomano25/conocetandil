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
                <button type="button" onclick="switchPayment('transfer')" id="tab-transfer"
                    class="flex-1 py-2.5 px-4 rounded-xl border-2 border-[#2D6A4F] bg-[#2D6A4F] text-white font-semibold text-sm transition">
                    Transferencia bancaria
                </button>
                <button type="button" onclick="switchPayment('mp')" id="tab-mp"
                    class="flex-1 py-2.5 px-4 rounded-xl border-2 border-gray-200 text-gray-600 font-semibold text-sm transition hover:border-[#2D6A4F]">
                    MercadoPago
                </button>
            </div>
        </div>
        @endif

            {{-- Transfer details --}}
            <div id="panel-transfer" class="{{ (!$bankTransferEnabled) ? 'hidden' : '' }} bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
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
            <div id="transfer-amount-reminder" class="{{ (!$bankTransferEnabled) ? 'hidden' : '' }} bg-[#2D6A4F]/5 border border-[#2D6A4F]/20 rounded-2xl p-5">
                <p class="text-sm text-[#2D6A4F] font-semibold mb-1">Monto a transferir</p>
                @if ($plan->hasSale())
                    <div class="flex items-baseline gap-2">
                        <s class="text-gray-400 text-lg">{{ $plan->formattedPrice() }}</s>
                        <span class="text-3xl font-bold text-[#2D6A4F]">{{ $plan->formattedEffectivePrice() }}</span>
                        @if ($plan->sale_label)
                            <span class="bg-amber-100 text-amber-700 text-xs rounded-full px-2 py-0.5 font-semibold">{{ $plan->sale_label }}</span>
                        @endif
                    </div>
                @else
                    <p class="text-3xl font-bold text-[#2D6A4F]" id="checkout-total-display">{{ $plan->formattedEffectivePrice() }}</p>
                @endif
                <p class="text-xs text-gray-500 mt-1">Plan {{ $plan->name }} · {{ $plan->durationLabel() }}</p>
            </div>

            {{-- Submit form --}}
            <div id="transfer-form-panel" class="{{ (!$bankTransferEnabled) ? 'hidden' : '' }} bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-bold text-[#1A1A1A] mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-[#2D6A4F] text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                    Confirmá tu pedido
                </h2>
                <p class="text-sm text-gray-500 mb-5">
                    Podés incluir el número de comprobante de la transferencia para que podamos verificarla más rápido.
                </p>

                <form id="membership-checkout-form" method="POST" action="{{ route('membership.store', $plan->slug) }}">
                    @csrf
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-membership">
                    <input type="hidden" name="promotion_id" id="promotion-id-input">

                    {{-- Coupon code --}}
                    <div class="mb-5 border border-gray-200 rounded-xl p-4">
                        <p class="text-sm font-semibold text-gray-700 mb-2">¿Tenés un código de descuento?</p>
                        <div class="flex gap-2">
                            <input type="text" id="coupon-code"
                                placeholder="CODIGO"
                                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                            <button type="button" onclick="validateCoupon()"
                                class="bg-[#2D6A4F] text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-[#1A1A1A] transition">
                                Aplicar
                            </button>
                        </div>
                        <div id="coupon-result" class="hidden mt-2 text-sm"></div>
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
                        Al confirmar, aceptás que revisaremos la transferencia y activaremos tu cuenta en menos de 24 hs.
                    </p>
                </form>
            </div>
            </div>{{-- /panel-transfer --}}

            {{-- MercadoPago panel --}}
            @if ($mercadopagoEnabled)
            @php
                $mpOrderExists = session('mp_order_id');
            @endphp
            <div id="panel-mp" class="{{ $bothEnabled ? 'hidden' : '' }} space-y-5">
                {{-- Amount reminder --}}
                <div class="bg-[#2D6A4F]/5 border border-[#2D6A4F]/20 rounded-2xl p-5">
                    <p class="text-sm text-[#2D6A4F] font-semibold mb-1">Total a pagar</p>
                    @if ($plan->hasSale())
                        <div class="flex items-baseline gap-2">
                            <s class="text-gray-400 text-lg">{{ $plan->formattedPrice() }}</s>
                            <span class="text-3xl font-bold text-[#2D6A4F]">{{ $plan->formattedEffectivePrice() }}</span>
                        </div>
                    @else
                        <p class="text-3xl font-bold text-[#2D6A4F]">{{ $plan->formattedEffectivePrice() }}</p>
                    @endif
                    <p class="text-xs text-gray-500 mt-1">Plan {{ $plan->name }} · {{ $plan->durationLabel() }}</p>
                </div>

                {{-- MP first: create order then redirect --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="font-bold text-[#1A1A1A] mb-4">Pagar con MercadoPago</h2>
                    <p class="text-sm text-gray-500 mb-6">
                        Al hacer clic serás redirigido a MercadoPago para completar el pago de forma segura. Podés pagar con tarjeta de crédito, débito o efectivo.
                    </p>

                    {{-- Step 1: Create order via transfer form (hidden), then show MP button --}}
                    <form id="mp-membership-form" method="POST" action="{{ route('membership.store', $plan->slug) }}">
                        @csrf
                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-mp-membership">
                        <input type="hidden" name="promotion_id" id="mp-promotion-id-input">
                        <input type="hidden" name="redirect_to_mp" value="1">

                        {{-- Coupon (shared logic) --}}
                        <div class="mb-5 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-2">¿Tenés un código de descuento?</p>
                            <div class="flex gap-2">
                                <input type="text" id="mp-coupon-code"
                                    placeholder="CODIGO"
                                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                                <button type="button" onclick="validateMpCoupon()"
                                    class="bg-[#2D6A4F] text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-[#1A1A1A] transition">
                                    Aplicar
                                </button>
                            </div>
                            <div id="mp-coupon-result" class="hidden mt-2 text-sm"></div>
                        </div>

                        <button type="submit"
                            class="w-full bg-[#009EE3] hover:bg-[#007ab8] text-white font-bold py-4 rounded-xl transition text-base flex items-center justify-center gap-3">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22.977 8.138c-.005-.018-.01-.036-.015-.054C22.004 4.461 18.39 2 14.222 2H7.652C6.234 2 5.03 3.02 4.812 4.422L2.014 19.578A1.5 1.5 0 003.5 21.25h4.11l1.032-6.55.032-.193c.218-1.402 1.422-2.422 2.84-2.422h.892c3.59 0 6.401-1.46 7.222-5.683.003-.017.006-.034.008-.05a4.27 4.27 0 00.07-.562c.024-.3.024-.614-.001-.902z"/>
                            </svg>
                            Pagar con MercadoPago
                        </button>
                        <p class="text-xs text-gray-400 text-center mt-3">
                            Serás redirigido al sitio seguro de MercadoPago.
                        </p>
                    </form>
                </div>
            </div>
            @endif

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
                    <span class="font-bold text-[#2D6A4F]">{{ $plan->formattedEffectivePrice() }}</span>
                </div>

                <div id="discount-row" class="hidden justify-between text-sm text-green-600 pb-2">
                    <span>Descuento</span>
                    <span id="discount-display">-$0</span>
                </div>

                <div class="flex justify-between text-sm font-bold text-[#1A1A1A] pt-1">
                    <span>Total</span>
                    <span id="total-display">{{ $plan->formattedEffectivePrice() }}</span>
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
@if ($bothEnabled ?? false)
function switchPayment(mode) {
    var transferPanels = ['panel-transfer', 'transfer-amount-reminder', 'transfer-form-panel'];
    var mpPanels = ['panel-mp'];

    if (mode === 'transfer') {
        transferPanels.forEach(id => { var el = document.getElementById(id); if(el) el.classList.remove('hidden'); });
        mpPanels.forEach(id => { var el = document.getElementById(id); if(el) el.classList.add('hidden'); });
        document.getElementById('tab-transfer').className = 'flex-1 py-2.5 px-4 rounded-xl border-2 border-[#2D6A4F] bg-[#2D6A4F] text-white font-semibold text-sm transition';
        document.getElementById('tab-mp').className = 'flex-1 py-2.5 px-4 rounded-xl border-2 border-gray-200 text-gray-600 font-semibold text-sm transition hover:border-[#2D6A4F]';
    } else {
        transferPanels.forEach(id => { var el = document.getElementById(id); if(el) el.classList.add('hidden'); });
        mpPanels.forEach(id => { var el = document.getElementById(id); if(el) el.classList.remove('hidden'); });
        document.getElementById('tab-mp').className = 'flex-1 py-2.5 px-4 rounded-xl border-2 border-[#2D6A4F] bg-[#2D6A4F] text-white font-semibold text-sm transition';
        document.getElementById('tab-transfer').className = 'flex-1 py-2.5 px-4 rounded-xl border-2 border-gray-200 text-gray-600 font-semibold text-sm transition hover:border-[#2D6A4F]';
    }
}
@endif

var mpAppliedDiscount = 0;
function validateMpCoupon() {
    var code = document.getElementById('mp-coupon-code').value.trim();
    if (!code) return;
    var result = document.getElementById('mp-coupon-result');
    result.className = 'mt-2 text-sm text-gray-500';
    result.textContent = 'Validando...';
    result.classList.remove('hidden');

    fetch('/api/validate-coupon?code=' + encodeURIComponent(code) + '&type=membership&plan_id={{ $plan->id }}&amount=' + baseAmount)
        .then(r => r.json())
        .then(data => {
            if (data.valid) {
                mpAppliedDiscount = data.discount;
                document.getElementById('mp-promotion-id-input').value = data.promotion_id;
                result.className = 'mt-2 text-sm text-green-600 font-semibold';
                result.textContent = '✓ ' + data.message;
            } else {
                mpAppliedDiscount = 0;
                document.getElementById('mp-promotion-id-input').value = '';
                result.className = 'mt-2 text-sm text-red-600';
                result.textContent = data.message;
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

var baseAmount = {{ $plan->effective_price }};
var appliedDiscount = 0;

function validateCoupon() {
    var code = document.getElementById('coupon-code').value.trim();
    if (!code) return;
    var result = document.getElementById('coupon-result');
    result.className = 'mt-2 text-sm text-gray-500';
    result.textContent = 'Validando...';
    result.classList.remove('hidden');

    fetch('/api/validate-coupon?code=' + encodeURIComponent(code) + '&type=membership&plan_id={{ $plan->id }}&amount=' + baseAmount)
        .then(r => r.json())
        .then(data => {
            if (data.valid) {
                appliedDiscount = data.discount;
                document.getElementById('promotion-id-input').value = data.promotion_id;
                result.className = 'mt-2 text-sm text-green-600 font-semibold';
                result.textContent = '✓ ' + data.message;
                var dr = document.getElementById('discount-row');
                dr.classList.remove('hidden'); dr.classList.add('flex');
                document.getElementById('discount-display').textContent = '-' + data.discount_formatted;
                var total = baseAmount - appliedDiscount;
                document.getElementById('total-display').textContent = '$' + total.toLocaleString('es-AR');
            } else {
                appliedDiscount = 0;
                document.getElementById('promotion-id-input').value = '';
                result.className = 'mt-2 text-sm text-red-600';
                result.textContent = data.message;
                var dr2 = document.getElementById('discount-row');
                dr2.classList.add('hidden'); dr2.classList.remove('flex');
                document.getElementById('total-display').textContent = '$' + baseAmount.toLocaleString('es-AR');
            }
        });
}

@if(\App\Models\Configuration::get('recaptcha_site_key'))
document.getElementById('membership-checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = this;
    grecaptcha.ready(function() {
        grecaptcha.execute('{{ \App\Models\Configuration::get("recaptcha_site_key") }}', {action: 'checkout'}).then(function(token) {
            document.getElementById('g-recaptcha-membership').value = token;
            form.submit();
        });
    });
});
@endif
</script>

@endsection
