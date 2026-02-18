@extends('layouts.app')
@section('title', 'Pedido confirmado — Conoce Tandil')

@section('content')

<section class="min-h-screen bg-gray-50 py-16">
<div class="max-w-lg mx-auto px-4 sm:px-6">

    {{-- Success icon --}}
    <div class="text-center mb-8">
        <div class="w-20 h-20 bg-[#2D6A4F]/10 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-[#1A1A1A]">¡Pedido recibido!</h1>
        <p class="text-gray-500 mt-2">
            Revisaremos tu transferencia y activaremos tu cuenta <strong>dentro de las próximas 24 horas</strong>.
        </p>
    </div>

    {{-- Order card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-7 mb-5">
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-gray-100">
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-widest">Pedido</p>
                <p class="text-lg font-bold text-[#1A1A1A]">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
            <span class="text-xs font-bold bg-amber-100 text-amber-700 px-3 py-1.5 rounded-full">
                Pendiente de verificación
            </span>
        </div>

        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Plan</span>
                <span class="font-semibold text-[#1A1A1A]">{{ $order->plan->name }} ({{ $order->plan->durationLabel() }})</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Total</span>
                <span class="font-semibold text-[#1A1A1A]">{{ $order->plan->formattedPrice() }}</span>
            </div>
            @if ($order->transfer_reference)
            <div class="flex justify-between">
                <span class="text-gray-500">Comprobante</span>
                <span class="font-semibold text-[#1A1A1A]">{{ $order->transfer_reference }}</span>
            </div>
            @endif
            <div class="flex justify-between">
                <span class="text-gray-500">Cuenta</span>
                <span class="font-semibold text-[#1A1A1A]">{{ auth()->user()->email }}</span>
            </div>
        </div>
    </div>

    {{-- Bank transfer reminder --}}
    @if ($bankConfig['cbu'] || $bankConfig['alias'])
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-7 mb-5">
        <h3 class="font-bold text-[#1A1A1A] mb-4">Datos para la transferencia</h3>
        <div class="space-y-2 text-sm">
            @if ($bankConfig['bank_name'])
            <div class="flex justify-between py-1.5 border-b border-gray-50">
                <span class="text-gray-500">Banco</span>
                <span class="font-semibold">{{ $bankConfig['bank_name'] }}</span>
            </div>
            @endif
            @if ($bankConfig['account_holder'])
            <div class="flex justify-between py-1.5 border-b border-gray-50">
                <span class="text-gray-500">Titular</span>
                <span class="font-semibold">{{ $bankConfig['account_holder'] }}</span>
            </div>
            @endif
            @if ($bankConfig['cbu'])
            <div class="flex justify-between py-1.5 border-b border-gray-50">
                <span class="text-gray-500">CBU</span>
                <button onclick="copyText('{{ $bankConfig['cbu'] }}', this)"
                    class="font-mono font-semibold text-[#2D6A4F] hover:underline">
                    {{ $bankConfig['cbu'] }}
                </button>
            </div>
            @endif
            @if ($bankConfig['alias'])
            <div class="flex justify-between py-1.5">
                <span class="text-gray-500">Alias</span>
                <button onclick="copyText('{{ $bankConfig['alias'] }}', this)"
                    class="font-semibold text-[#2D6A4F] hover:underline">
                    {{ $bankConfig['alias'] }}
                </button>
            </div>
            @endif
        </div>
        <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-xs text-amber-700 font-medium">
            Acordate de incluir tu comprobante de transferencia si aún no lo hiciste.
        </div>
    </div>
    @endif

    {{-- Steps --}}
    <div class="space-y-3 mb-8">
        @foreach([
            ['✅', 'Pedido registrado', 'Tu solicitud fue guardada correctamente.'],
            ['🕐', 'Verificación en proceso', 'Revisaremos tu transferencia en las próximas horas.'],
            ['✦',  'Acceso Premium activado', 'Recibirás tu acceso una vez confirmado el pago.'],
        ] as [$icon, $title, $desc])
        <div class="flex items-start gap-3 bg-white rounded-xl border border-gray-100 px-4 py-3">
            <span class="text-xl flex-shrink-0">{{ $icon }}</span>
            <div>
                <p class="text-sm font-semibold text-[#1A1A1A]">{{ $title }}</p>
                <p class="text-xs text-gray-400">{{ $desc }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="text-center space-y-3">
        <a href="{{ route('inicio') }}"
            class="block bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-bold py-3.5 rounded-xl transition">
            Volver al inicio
        </a>
        <a href="{{ route('contacto') }}" class="block text-sm text-gray-500 hover:text-[#2D6A4F]">
            ¿Tenés dudas? Contactanos
        </a>
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
