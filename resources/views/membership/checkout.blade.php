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

    {{-- Inline auth for guests --}}
    @if (!auth()->check())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 bg-[#2D6A4F]/10 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-[#1A1A1A] text-sm">Creá tu cuenta para continuar</p>
                <p class="text-xs text-gray-500">Solo te lleva un momento — sin tarjeta de crédito</p>
            </div>
        </div>

        {{-- Toggle tabs --}}
        <div class="flex gap-1 bg-gray-100 rounded-xl p-1 mb-5">
            <button type="button" id="tab-btn-register" onclick="switchAuthTab('register')"
                class="flex-1 py-2 px-4 rounded-lg text-sm font-semibold transition bg-white text-[#1A1A1A] shadow-sm">
                Crear cuenta
            </button>
            <button type="button" id="tab-btn-login" onclick="switchAuthTab('login')"
                class="flex-1 py-2 px-4 rounded-lg text-sm font-semibold transition text-gray-500 hover:text-[#1A1A1A]">
                Ya tengo cuenta
            </button>
        </div>

        {{-- Register form --}}
        <form id="auth-register-form" method="POST" action="{{ route('membership.checkout.register', $plan->slug) }}">
            @csrf
            <input type="hidden" name="auth_mode" value="register">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name"
                        placeholder="Tu nombre"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                        placeholder="tu@email.com"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" name="password" required autocomplete="new-password"
                        placeholder="Mínimo 8 caracteres"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent @error('password') border-red-400 @enderror">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmá la contraseña</label>
                    <input type="password" name="password_confirmation" required autocomplete="new-password"
                        placeholder="Repetí la contraseña"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent">
                </div>
                <button type="submit"
                    class="w-full bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-bold py-3.5 rounded-xl transition text-sm">
                    Crear cuenta y continuar →
                </button>
            </div>
        </form>

        {{-- Login form --}}
        <form id="auth-login-form" method="POST" action="{{ route('membership.checkout.register', $plan->slug) }}" class="hidden">
            @csrf
            <input type="hidden" name="auth_mode" value="login">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                        placeholder="tu@email.com"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent @error('login_password') border-red-400 @enderror">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" name="password" required autocomplete="current-password"
                        placeholder="Tu contraseña"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent @error('login_password') border-red-400 @enderror">
                    @error('login_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit"
                    class="w-full bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-bold py-3.5 rounded-xl transition text-sm">
                    Iniciar sesión y continuar →
                </button>
            </div>
        </form>
    </div>
    @endif

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

    @if (auth()->check())
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

            {{-- MercadoPago panel --}}
            @if ($mercadopagoEnabled)
            <div id="panel-mp" class="{{ $bothEnabled ? 'hidden' : '' }} space-y-4">

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
                                @if ($plan->hasSale())
                                    <s class="text-gray-400 text-sm block">{{ $plan->formattedPrice() }}</s>
                                    <span class="text-xl font-bold text-[#2D6A4F]" id="mp-total-display">{{ $plan->formattedEffectivePrice() }}</span>
                                @else
                                    <span class="text-xl font-bold text-[#2D6A4F]" id="mp-total-display">{{ $plan->formattedEffectivePrice() }}</span>
                                @endif
                            </div>
                        </div>

                        <form id="mp-membership-form" method="POST" action="{{ route('membership.store', $plan->slug) }}">
                            @csrf
                            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-mp-membership">
                            <input type="hidden" name="promotion_id" id="mp-promotion-id-input">
                            <input type="hidden" name="redirect_to_mp" value="1">

                            {{-- Coupon --}}
                            <div class="mb-5">
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

                            <button type="submit" id="mp-membership-btn"
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

                @if (auth()->check())
                <div class="mt-5 pt-4 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-400">Cuenta: <strong>{{ auth()->user()->email }}</strong></p>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
    @else
    {{-- Guest: compact plan summary --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Tu plan</p>
        <div class="flex items-center justify-between">
            <div>
                <p class="font-bold text-[#1A1A1A]">{{ $plan->name }}</p>
                <p class="text-xs text-gray-400">{{ $plan->durationLabel() }} de acceso Premium</p>
            </div>
            <span class="font-bold text-[#2D6A4F] text-lg">{{ $plan->formattedEffectivePrice() }}</span>
        </div>
        @if (!empty($plan->features))
        <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
            @foreach ($plan->features as $feature)
            <div class="flex items-start gap-2 text-xs text-gray-500">
                <svg class="w-3.5 h-3.5 text-[#2D6A4F] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $feature }}
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endif
</section>

<script>
@if ($bothEnabled ?? false)
document.addEventListener('DOMContentLoaded', function() {
    @if(session('open_mp_tab'))
    switchPayment('mp');
    @endif
});

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
                var total = baseAmount - data.discount;
                document.getElementById('mp-total-display').textContent = '$' + total.toLocaleString('es-AR');
            } else {
                mpAppliedDiscount = 0;
                document.getElementById('mp-promotion-id-input').value = '';
                result.className = 'mt-2 text-sm text-red-600';
                result.textContent = data.message;
                document.getElementById('mp-total-display').textContent = '$' + baseAmount.toLocaleString('es-AR');
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
['membership-checkout-form', 'mp-membership-form'].forEach(function(formId) {
    var form = document.getElementById(formId);
    if (!form) return;
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var f = this;
        var tokenField = formId === 'mp-membership-form' ? 'g-recaptcha-mp-membership' : 'g-recaptcha-membership';
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ \App\Models\Configuration::get("recaptcha_site_key") }}', {action: 'checkout'}).then(function(token) {
                document.getElementById(tokenField).value = token;
                f.submit();
            });
        });
    });
});
@endif
</script>

@if (!auth()->check())
<script>
function switchAuthTab(tab) {
    var regForm  = document.getElementById('auth-register-form');
    var logForm  = document.getElementById('auth-login-form');
    var btnReg   = document.getElementById('tab-btn-register');
    var btnLog   = document.getElementById('tab-btn-login');

    if (tab === 'register') {
        regForm.classList.remove('hidden');
        logForm.classList.add('hidden');
        btnReg.className = 'flex-1 py-2 px-4 rounded-lg text-sm font-semibold transition bg-white text-[#1A1A1A] shadow-sm';
        btnLog.className = 'flex-1 py-2 px-4 rounded-lg text-sm font-semibold transition text-gray-500 hover:text-[#1A1A1A]';
    } else {
        regForm.classList.add('hidden');
        logForm.classList.remove('hidden');
        btnLog.className = 'flex-1 py-2 px-4 rounded-lg text-sm font-semibold transition bg-white text-[#1A1A1A] shadow-sm';
        btnReg.className = 'flex-1 py-2 px-4 rounded-lg text-sm font-semibold transition text-gray-500 hover:text-[#1A1A1A]';
    }
}
@php
    // If there were login errors, pre-switch to login tab
    $openLoginTab = $errors->has('login_password');
@endphp
@if ($openLoginTab)
document.addEventListener('DOMContentLoaded', function() { switchAuthTab('login'); });
@endif
</script>
@endif

@endsection
