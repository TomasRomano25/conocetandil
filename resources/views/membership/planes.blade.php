@extends('layouts.app')
@section('title', 'Planes Premium — Conoce Tandil')

@section('content')

<div class="planes-wrapper">

    {{-- Hero --}}
    <div class="planes-hero">
        <div class="planes-hero__inner">
            <p class="planes-hero__eyebrow">Acceso Premium</p>
            <h1 class="planes-hero__title">Elegí la duración ideal<br>para tu viaje.</h1>
            <p class="planes-hero__sub">Pagás solo por el tiempo que necesitás. Sin renovación automática.</p>
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

                <div class="plan-card {{ $popular ? 'plan-card--popular' : 'plan-card--secondary' }}">

                    @if ($popular)
                        <div class="plan-glow"></div>
                    @endif

                    {{-- Badge: fila siempre presente --}}
                    <div class="plan-badge-row">
                        @if ($popular)
                            <span class="plan-badge plan-badge--popular">
                                <svg width="9" height="9" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                Más elegido
                            </span>
                        @elseif ($onSale)
                            <span class="plan-badge plan-badge--sale">Precio lanzamiento</span>
                        @endif
                    </div>

                    <div class="plan-body">

                        {{-- Nombre --}}
                        <h3 class="plan-name">{{ $plan->name }}</h3>

                        {{-- Descripción: altura fija --}}
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
                                @if ($onSale)
                                    <s class="plan-price-original">{{ $plan->formattedPrice() }}</s>
                                @endif
                            </div>
                            <p class="plan-price-access">Acceso completo por {{ $plan->durationLabel() }}</p>
                            {{-- Precio por mes: espacio fijo reservado --}}
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
                            {{ $popular ? 'Comenzar mi plan' : 'Suscribirme' }}
                        </a>

                        @if ($popular)
                            <p class="plan-cta-note">Pago único · Sin renovación automática</p>
                        @endif

                    </div>
                </div>
                @endforeach
            </div>

            @endif

            <div class="planes-footer">
                <p>💳 Pago por transferencia bancaria · Acceso activado dentro de las 24 hs</p>
                <p>¿Dudas? <a href="{{ route('contacto') }}">Contactanos</a></p>
            </div>

        </div>
    </div>

</div>

<style>
/* ─── Wrapper ──────────────────────────────────────── */
.planes-wrapper {
    background: linear-gradient(160deg, #0f1a14 0%, #16281e 50%, #1e3529 100%);
    min-height: 100vh;
}

/* ─── Hero ─────────────────────────────────────────── */
.planes-hero { padding: 5rem 1.5rem 3rem; text-align: center; }
.planes-hero__inner { max-width: 620px; margin: 0 auto; }
.planes-hero__eyebrow {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: #52B788;
    margin-bottom: 1rem;
}
.planes-hero__title {
    font-size: clamp(2rem, 5vw, 3.25rem);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.03em;
    color: #fff;
    margin-bottom: 0.85rem;
}
.planes-hero__sub {
    font-size: 0.95rem;
    color: rgba(255,255,255,0.38);
}

/* ─── Section ──────────────────────────────────────── */
.planes-section { padding: 0 1.25rem 5rem; }
.planes-container { max-width: 1080px; margin: 0 auto; }
.planes-empty { text-align: center; padding: 5rem 0; color: rgba(255,255,255,0.3); font-size: 0.9rem; }

/* ─── Grid ─────────────────────────────────────────── */
.planes-grid {
    display: grid;
    gap: 1rem;
    align-items: end; /* las cards se alinean desde abajo → el popular puede ser más alto sin romper layout */
}
.planes-grid--1 { grid-template-columns: minmax(0, 420px); justify-content: center; }
.planes-grid--2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
.planes-grid--3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
.planes-grid--4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }

@media (max-width: 860px) {
    .planes-grid--3,
    .planes-grid--4 { grid-template-columns: repeat(2, minmax(0, 1fr)); align-items: stretch; }
}
@media (max-width: 540px) {
    .planes-grid--2,
    .planes-grid--3,
    .planes-grid--4 { grid-template-columns: minmax(0, 1fr); align-items: stretch; }
}

/* ─── Card base ────────────────────────────────────── */
.plan-card {
    position: relative;
    display: flex;
    flex-direction: column;
    border-radius: 1.125rem;
    overflow: hidden;
    transition: box-shadow 0.25s;
}

/* Cards secundarias: apagadas, sin protagonismo */
.plan-card--secondary {
    background: rgba(255,255,255,0.035);
    border: 1px solid rgba(255,255,255,0.07);
}
.plan-card--secondary:hover {
    box-shadow: 0 6px 28px rgba(0,0,0,0.25);
    border-color: rgba(255,255,255,0.11);
}

/* Card popular: grande, brillante, dominante */
.plan-card--popular {
    background: rgba(82,183,136,0.08);
    border: 1px solid rgba(82,183,136,0.4);
    box-shadow:
        0 20px 60px rgba(0,0,0,0.45),
        0 0 80px rgba(45,106,79,0.18);
    /* Extra padding para que sea más grande que las demás */
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
}
.plan-card--popular:hover {
    box-shadow:
        0 24px 70px rgba(0,0,0,0.5),
        0 0 90px rgba(82,183,136,0.22);
    border-color: rgba(82,183,136,0.55);
}

/* Glow radial verde detrás del popular */
.plan-glow {
    position: absolute;
    top: -40px; left: -40px; right: -40px;
    height: 220px;
    pointer-events: none;
    background: radial-gradient(ellipse at 50% 0%, rgba(82,183,136,0.22) 0%, transparent 68%);
    z-index: 0;
}

/* ─── Badge row: altura fija en todas las cards ─────── */
.plan-badge-row {
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 1;
    flex-shrink: 0;
}
.plan-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.6rem;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    padding: 0.28rem 0.75rem;
    border-radius: 999px;
}
.plan-badge--popular {
    background: rgba(82,183,136,0.15);
    color: #52B788;
    border: 1px solid rgba(82,183,136,0.4);
}
.plan-badge--sale {
    background: rgba(255,255,255,0.05);
    color: rgba(255,255,255,0.35);
    border: 1px solid rgba(255,255,255,0.08);
}

/* ─── Body ─────────────────────────────────────────── */
.plan-body {
    display: flex;
    flex-direction: column;
    flex: 1;
    padding: 0 1.35rem 1.5rem;
    position: relative;
    z-index: 1;
}
.plan-card--popular .plan-body {
    padding: 0 1.75rem 2rem; /* más respiración en el popular */
}

/* ─── Nombre ────────────────────────────────────────── */
.plan-name {
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: -0.01em;
    margin-bottom: 0.35rem;
    color: rgba(255,255,255,0.55); /* secundario: tenue */
}
.plan-card--popular .plan-name {
    font-size: 1.2rem;
    font-weight: 800;
    color: #ffffff; /* popular: blanco total */
}

/* ─── Descripción ───────────────────────────────────── */
.plan-desc-wrap {
    height: 2.4rem;
    margin-bottom: 1.1rem;
    overflow: hidden;
}
.plan-desc {
    font-size: 0.72rem;
    color: rgba(255,255,255,0.25); /* secundario: muy tenue */
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.plan-card--popular .plan-desc {
    color: rgba(255,255,255,0.45); /* popular: más visible */
    font-size: 0.8rem;
}

/* ─── Divisor ───────────────────────────────────────── */
.plan-divider {
    height: 1px;
    background: rgba(255,255,255,0.06);
    margin-bottom: 1.1rem;
}
.plan-card--popular .plan-divider {
    background: rgba(82,183,136,0.2);
}

/* ─── Precio ────────────────────────────────────────── */
.plan-price-block { margin-bottom: 1.35rem; }

.plan-price-main {
    display: flex;
    align-items: flex-start;
    gap: 0.15rem;
    line-height: 1;
    margin-bottom: 0.5rem;
}
.plan-price-currency {
    font-size: 0.9rem;
    font-weight: 600;
    margin-top: 0.35rem;
    color: rgba(255,255,255,0.35); /* secundario */
}
.plan-card--popular .plan-price-currency {
    font-size: 1.1rem;
    color: rgba(255,255,255,0.5);
    margin-top: 0.55rem;
}
.plan-price-amount {
    font-size: 2rem;
    font-weight: 800;
    letter-spacing: -0.04em;
    color: rgba(255,255,255,0.55); /* secundario: apagado */
}
.plan-card--popular .plan-price-amount {
    font-size: 3rem; /* popular: precio dominante */
    color: #ffffff;
}
.plan-price-original {
    font-size: 0.7rem;
    color: rgba(255,255,255,0.2);
    text-decoration: line-through;
    align-self: flex-end;
    margin-bottom: 0.3rem;
    margin-left: 0.3rem;
}
/* Línea de acceso (solo visible, más clara en popular) */
.plan-price-access {
    font-size: 0.72rem;
    color: rgba(255,255,255,0.28);
    margin-bottom: 0.2rem;
}
.plan-card--popular .plan-price-access {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.5);
    font-weight: 500;
}
/* Precio por mes: altura fija reservada */
.plan-price-permonth {
    height: 1.1rem;
    font-size: 0.68rem;
    color: rgba(82,183,136,0.5); /* secundario: muy apagado */
}
.plan-card--popular .plan-price-permonth {
    color: #52B788; /* popular: verde brillante */
    font-weight: 600;
    font-size: 0.75rem;
}

/* ─── Features ──────────────────────────────────────── */
.plan-features {
    list-style: none;
    padding: 0;
    margin: 0 0 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.55rem;
}
.plan-feature {
    display: flex;
    align-items: flex-start;
    gap: 0.55rem;
    font-size: 0.75rem;
    color: rgba(255,255,255,0.3); /* secundario: tenue */
    line-height: 1.4;
}
.plan-card--popular .plan-feature {
    font-size: 0.82rem;
    color: rgba(255,255,255,0.7); /* popular: legible */
    gap: 0.65rem;
}
.plan-feature__icon {
    width: 0.875rem;
    height: 0.875rem;
    flex-shrink: 0;
    margin-top: 0.1rem;
    color: rgba(82,183,136,0.4); /* secundario: apagado */
}
.plan-card--popular .plan-feature__icon {
    width: 1rem;
    height: 1rem;
    color: #52B788; /* popular: verde vivo */
}

/* ─── CTA ───────────────────────────────────────────── */
.plan-cta {
    display: block;
    text-align: center;
    font-weight: 700;
    border-radius: 0.75rem;
    letter-spacing: 0.01em;
    transition: all 0.2s;
    text-decoration: none;
}
/* Secundario: outline muy sutil, casi no visible */
.plan-cta--default {
    font-size: 0.8rem;
    padding: 0.6rem 1rem;
    background: transparent;
    color: rgba(255,255,255,0.35);
    border: 1px solid rgba(255,255,255,0.1);
}
.plan-cta--default:hover {
    color: rgba(255,255,255,0.7);
    border-color: rgba(255,255,255,0.2);
    background: rgba(255,255,255,0.04);
}
/* Popular: sólido, grande, llamativo */
.plan-cta--popular {
    font-size: 0.95rem;
    padding: 0.9rem 1rem;
    background: #2D6A4F;
    color: #ffffff;
    box-shadow: 0 4px 20px rgba(45,106,79,0.5);
}
.plan-cta--popular:hover {
    background: #52B788;
    box-shadow: 0 6px 28px rgba(82,183,136,0.4);
}

/* Nota bajo CTA del popular */
.plan-cta-note {
    margin-top: 0.65rem;
    text-align: center;
    font-size: 0.65rem;
    color: rgba(255,255,255,0.22);
    letter-spacing: 0.04em;
}

/* ─── Footer ────────────────────────────────────────── */
.planes-footer {
    margin-top: 3.5rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}
.planes-footer p { font-size: 0.75rem; color: rgba(255,255,255,0.2); }
.planes-footer a { color: rgba(82,183,136,0.7); text-decoration: none; font-weight: 500; }
.planes-footer a:hover { color: #52B788; }
</style>

@endsection
