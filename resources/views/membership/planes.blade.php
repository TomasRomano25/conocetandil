@extends('layouts.app')
@section('title', 'Planes Premium — Conoce Tandil')

@section('content')

<div class="planes-wrapper">

    {{-- Hero --}}
    <div class="planes-hero">
        <div class="planes-hero__inner">
            <p class="planes-hero__eyebrow">Elegí tu acceso</p>
            <h1 class="planes-hero__title">Explorá Tandil<br>como nunca antes.</h1>
            <p class="planes-hero__sub">Pagás solo por el tiempo que necesitás.</p>
        </div>
    </div>

    {{-- Plans --}}
    <div class="planes-section">
        <div class="planes-container">

            @if ($plans->isEmpty())
                <p class="planes-empty">No hay planes disponibles en este momento.</p>
            @else

            <div class="planes-grid planes-grid--{{ min($plans->count(), 4) }}">
                @foreach ($plans as $plan)
                @php
                    $popular = $plan->is_popular;
                    $onSale  = $plan->hasSale();
                @endphp

                <div class="plan-card {{ $popular ? 'plan-card--popular' : '' }}">

                    @if ($popular)
                        <div class="plan-glow"></div>
                    @endif

                    {{-- Badge row: siempre ocupa espacio --}}
                    <div class="plan-badge-row">
                        @if ($popular)
                            <span class="plan-badge plan-badge--popular">
                                <svg width="10" height="10" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                Más elegido
                            </span>
                        @elseif ($onSale)
                            <span class="plan-badge plan-badge--sale">Precio lanzamiento</span>
                        @endif
                    </div>

                    <div class="plan-body">

                        {{-- Nombre --}}
                        <h3 class="plan-name">{{ $plan->name }}</h3>

                        {{-- Descripción --}}
                        <div class="plan-desc-wrap">
                            @if ($plan->description)
                                <p class="plan-desc">{{ $plan->description }}</p>
                            @endif
                        </div>

                        <div class="plan-divider"></div>

                        {{-- Precio --}}
                        <div class="plan-price-block">
                            <div class="plan-price-main">
                                <span class="plan-price-currency">$</span>
                                <span class="plan-price-amount">{{ number_format($plan->effective_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="plan-price-meta">
                                @if ($onSale)
                                    <s class="plan-price-original">{{ $plan->formattedPrice() }}</s>
                                @endif
                                <span class="plan-price-duration">/ {{ $plan->durationLabel() }}</span>
                            </div>
                            {{-- Precio por mes: espacio reservado --}}
                            <div class="plan-price-permonth">
                                @if ($plan->duration_months > 1 && $plan->duration_unit === 'months')
                                    ≈ ${{ number_format($plan->effective_price / $plan->duration_months, 0, ',', '.') }} por mes
                                @endif
                            </div>
                        </div>

                        {{-- Features --}}
                        <ul class="plan-features">
                            @forelse ($plan->features ?? [] as $feature)
                            <li class="plan-feature">
                                <svg class="plan-feature__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $feature }}
                            </li>
                            @empty
                            @endforelse
                        </ul>

                        {{-- CTA --}}
                        <a href="{{ route('membership.checkout', $plan->slug) }}"
                            class="plan-cta {{ $popular ? 'plan-cta--popular' : 'plan-cta--default' }}">
                            Suscribirme
                        </a>

                    </div>
                </div>
                @endforeach
            </div>

            @endif

            <div class="planes-footer">
                <p>💳 Pago por transferencia bancaria · Acceso activado en 24 hs</p>
                <p>¿Dudas? <a href="{{ route('contacto') }}">Contactanos</a></p>
            </div>

        </div>
    </div>

</div>

<style>
/* ─── Wrapper ──────────────────────────────────────────── */
.planes-wrapper {
    background: linear-gradient(160deg, #0f1a14 0%, #16281e 45%, #1e3529 100%);
    min-height: 100vh;
}

/* ─── Hero ─────────────────────────────────────────────── */
.planes-hero {
    padding: 5rem 1.5rem 3.5rem;
    text-align: center;
}
.planes-hero__inner {
    max-width: 640px;
    margin: 0 auto;
}
.planes-hero__eyebrow {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #52B788;
    margin-bottom: 1rem;
}
.planes-hero__title {
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.03em;
    color: #ffffff;
    margin-bottom: 1rem;
}
.planes-hero__sub {
    font-size: 1rem;
    color: rgba(255,255,255,0.45);
    font-weight: 400;
}

/* ─── Section ──────────────────────────────────────────── */
.planes-section {
    padding: 0 1.5rem 5rem;
}
.planes-container {
    max-width: 1024px;
    margin: 0 auto;
}
.planes-empty {
    text-align: center;
    padding: 5rem 0;
    color: rgba(255,255,255,0.3);
}

/* ─── Grid ─────────────────────────────────────────────── */
.planes-grid {
    display: grid;
    gap: 1rem;
    align-items: stretch;
}
.planes-grid--1 { grid-template-columns: minmax(0, 400px); justify-content: center; }
.planes-grid--2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
.planes-grid--3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
.planes-grid--4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }

@media (max-width: 900px) {
    .planes-grid--3,
    .planes-grid--4 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 560px) {
    .planes-grid--2,
    .planes-grid--3,
    .planes-grid--4 { grid-template-columns: minmax(0, 1fr); }
}

/* ─── Card base ─────────────────────────────────────────── */
.plan-card {
    position: relative;
    display: flex;
    flex-direction: column;
    border-radius: 1.25rem;
    overflow: hidden;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    transition: border-color 0.2s, box-shadow 0.2s;
}
.plan-card:hover {
    border-color: rgba(255,255,255,0.14);
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
}

/* ─── Card popular ──────────────────────────────────────── */
.plan-card--popular {
    background: rgba(82, 183, 136, 0.07);
    border-color: rgba(82, 183, 136, 0.35);
    box-shadow:
        0 10px 40px rgba(0,0,0,0.35),
        0 0 60px rgba(62, 145, 95, 0.12);
}
.plan-card--popular:hover {
    border-color: rgba(82, 183, 136, 0.55);
    box-shadow:
        0 14px 50px rgba(0,0,0,0.4),
        0 0 70px rgba(62, 145, 95, 0.18);
}

/* Glow radial detrás del card popular */
.plan-glow {
    position: absolute;
    inset: 0;
    pointer-events: none;
    background: radial-gradient(ellipse at 50% 0%, rgba(82,183,136,0.18) 0%, transparent 70%);
    z-index: 0;
}

/* ─── Badge row ─────────────────────────────────────────── */
.plan-badge-row {
    height: 2.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 1;
}
.plan-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.65rem;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 0.3rem 0.8rem;
    border-radius: 999px;
}
.plan-badge--popular {
    background: rgba(82, 183, 136, 0.18);
    color: #52B788;
    border: 1px solid rgba(82, 183, 136, 0.35);
}
.plan-badge--sale {
    background: rgba(255,255,255,0.06);
    color: rgba(255,255,255,0.45);
    border: 1px solid rgba(255,255,255,0.1);
}

/* ─── Body ──────────────────────────────────────────────── */
.plan-body {
    display: flex;
    flex-direction: column;
    flex: 1;
    padding: 0 1.5rem 1.75rem;
    position: relative;
    z-index: 1;
}

/* ─── Nombre ────────────────────────────────────────────── */
.plan-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.4rem;
    letter-spacing: -0.01em;
}

/* ─── Descripción: altura fija ──────────────────────────── */
.plan-desc-wrap {
    height: 2.5rem;
    margin-bottom: 1.25rem;
    overflow: hidden;
}
.plan-desc {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.35);
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ─── Divisor ───────────────────────────────────────────── */
.plan-divider {
    height: 1px;
    background: rgba(255,255,255,0.07);
    margin-bottom: 1.25rem;
}

/* ─── Precio ────────────────────────────────────────────── */
.plan-price-block {
    margin-bottom: 1.5rem;
}
.plan-price-main {
    display: flex;
    align-items: flex-start;
    gap: 0.2rem;
    line-height: 1;
    margin-bottom: 0.4rem;
}
.plan-price-currency {
    font-size: 1rem;
    font-weight: 600;
    color: rgba(255,255,255,0.5);
    margin-top: 0.4rem;
}
.plan-price-amount {
    font-size: 2.5rem;
    font-weight: 800;
    letter-spacing: -0.04em;
    color: #ffffff;
}
.plan-price-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}
.plan-price-original {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.25);
    text-decoration: line-through;
}
.plan-price-duration {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.35);
}
/* Altura fija para precio-por-mes */
.plan-price-permonth {
    height: 1.25rem;
    font-size: 0.7rem;
    color: #52B788;
    margin-top: 0.35rem;
}

/* ─── Features ──────────────────────────────────────────── */
.plan-features {
    list-style: none;
    padding: 0;
    margin: 0 0 1.75rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
}
.plan-feature {
    display: flex;
    align-items: flex-start;
    gap: 0.6rem;
    font-size: 0.8rem;
    color: rgba(255,255,255,0.55);
    line-height: 1.4;
}
.plan-feature__icon {
    width: 1rem;
    height: 1rem;
    flex-shrink: 0;
    color: #52B788;
    margin-top: 0.05rem;
}
.plan-card--popular .plan-feature {
    color: rgba(255,255,255,0.75);
}

/* ─── CTA ───────────────────────────────────────────────── */
.plan-cta {
    display: block;
    text-align: center;
    font-size: 0.875rem;
    font-weight: 700;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    letter-spacing: 0.01em;
    transition: all 0.2s;
    text-decoration: none;
}
.plan-cta--popular {
    background: #2D6A4F;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(45, 106, 79, 0.4);
}
.plan-cta--popular:hover {
    background: #52B788;
    box-shadow: 0 6px 20px rgba(82, 183, 136, 0.35);
}
.plan-cta--default {
    background: transparent;
    color: rgba(255,255,255,0.6);
    border: 1px solid rgba(255,255,255,0.12);
}
.plan-cta--default:hover {
    background: rgba(255,255,255,0.06);
    color: #ffffff;
    border-color: rgba(255,255,255,0.2);
}

/* ─── Footer ────────────────────────────────────────────── */
.planes-footer {
    margin-top: 3rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}
.planes-footer p {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.25);
}
.planes-footer a {
    color: #52B788;
    text-decoration: none;
    font-weight: 500;
}
.planes-footer a:hover {
    text-decoration: underline;
}
</style>

@endsection
