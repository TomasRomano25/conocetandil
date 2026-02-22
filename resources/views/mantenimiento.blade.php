<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volvemos pronto — Conoce Tandil</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green-dark: #2D6A4F;
            --green-light: #52B788;
            --black: #1A1A1A;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8faf9;
            color: var(--black);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Subtle background pattern */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(82, 183, 136, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(45, 106, 79, 0.07) 0%, transparent 50%),
                radial-gradient(circle at 60% 10%, rgba(82, 183, 136, 0.05) 0%, transparent 40%);
            pointer-events: none;
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%232D6A4F' fill-opacity='0.025'%3E%3Ccircle cx='30' cy='30' r='1.5'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 640px;
            width: 100%;
            padding: 2rem 1.5rem;
            text-align: center;
        }

        /* Status indicator */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(45, 106, 79, 0.08);
            border: 1px solid rgba(45, 106, 79, 0.15);
            border-radius: 100px;
            padding: 0.35rem 0.85rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--green-dark);
            letter-spacing: 0.02em;
            margin-bottom: 2.5rem;
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            background: var(--green-light);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.75); }
        }

        /* Logo / Brand */
        .brand {
            margin-bottom: 1.5rem;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--green-dark), var(--green-light));
            border-radius: 16px;
            margin-bottom: 1rem;
            box-shadow: 0 4px 20px rgba(45, 106, 79, 0.2);
        }

        .brand-logo svg {
            width: 28px;
            height: 28px;
            color: #fff;
        }

        .brand-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--green-dark);
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        /* Headline */
        h1 {
            font-size: clamp(2rem, 5vw, 2.75rem);
            font-weight: 700;
            color: var(--black);
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 1rem;
        }

        h1 span {
            background: linear-gradient(135deg, var(--green-dark), var(--green-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-size: 1.05rem;
            color: #6b7280;
            line-height: 1.65;
            font-weight: 400;
            margin-bottom: 3rem;
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Feature cards */
        .features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        @media (max-width: 480px) {
            .features { grid-template-columns: 1fr; }
        }

        .feature-card {
            background: #fff;
            border: 1px solid rgba(45, 106, 79, 0.1);
            border-radius: 14px;
            padding: 1.25rem 1.25rem 1.35rem;
            text-align: left;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .feature-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(45, 106, 79, 0.1);
        }

        .feature-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, rgba(45, 106, 79, 0.1), rgba(82, 183, 136, 0.12));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.85rem;
        }

        .feature-icon svg {
            width: 18px;
            height: 18px;
            color: var(--green-dark);
        }

        .feature-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--black);
            margin-bottom: 0.3rem;
        }

        .feature-desc {
            font-size: 0.78rem;
            color: #6b7280;
            line-height: 1.55;
        }

        /* Divider */
        .divider {
            width: 40px;
            height: 2px;
            background: linear-gradient(to right, var(--green-dark), var(--green-light));
            border-radius: 2px;
            margin: 0 auto 2.5rem;
        }

        /* Footer */
        .footer {
            font-size: 0.75rem;
            color: #9ca3af;
            line-height: 1.7;
        }

        .footer a {
            color: var(--green-dark);
            text-decoration: none;
            font-weight: 500;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Floating shapes */
        .shape {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            opacity: 0.04;
        }

        .shape-1 {
            width: 400px;
            height: 400px;
            background: var(--green-light);
            top: -120px;
            right: -100px;
            animation: float 8s ease-in-out infinite;
        }

        .shape-2 {
            width: 300px;
            height: 300px;
            background: var(--green-dark);
            bottom: -80px;
            left: -80px;
            animation: float 10s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body>

    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="container">

        <div class="status-badge">
            <span class="pulse-dot"></span>
            Volvemos pronto
        </div>

        <div class="brand">
            <div class="brand-logo">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="brand-name">Conoce Tandil</div>
        </div>

        <h1>Estamos preparando<br><span>algo especial</span></h1>

        <p class="subtitle">
            La guía definitiva para descubrir Tandil está casi lista.
            Pronto podrás explorar los mejores lugares, paisajes y experiencias de la ciudad.
        </p>

        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="feature-title">Lugares</div>
                <div class="feature-desc">Descubrí los mejores lugares de Tandil — cerros, naturaleza, gastronomía y más.</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="feature-title">Itinerarios</div>
                <div class="feature-desc">Planificá tu visita perfecta con itinerarios personalizados para cada estilo de viaje.</div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="footer">
            <p>¿Querés recibir novedades? Escribinos a <a href="mailto:comercial@conocetandil.com">comercial@conocetandil.com</a></p>
            <p style="margin-top: 0.5rem;">© {{ date('Y') }} Conoce Tandil — Tandil, Buenos Aires, Argentina</p>
        </div>

    </div>

</body>
</html>
