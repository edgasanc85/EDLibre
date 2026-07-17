<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EDLibre - Evaluación del Desempeño Laboral (LNR)</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="EDLibre es la alternativa libre y gratuita para la Evaluación del Desempeño Laboral (LNR) de entidades públicas en Colombia tras la restricción de EDL-APP v2.17.0 por la CNSC.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <!-- Vite CSS -->
    @vite(['resources/sass/app.scss'])

    <style>
        :root {
            --bg-color: #050505;
            --bg-surface: #111111;
            --primary-gradient: linear-gradient(135deg, #00F0FF 0%, #0057FF 100%);
            --secondary-gradient: linear-gradient(135deg, #FF007A 0%, #7000FF 100%);
            --text-main: #FFFFFF;
            --text-muted: #A1A1AA;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
            --font-display: 'Outfit', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: var(--font-display);
        }

        /* Glassmorphism Navbar */
        .navbar-custom {
            background-color: rgba(5, 5, 5, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-glow {
            position: relative;
            background: var(--primary-gradient);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            overflow: hidden;
            transition: all 0.3s ease;
            font-family: var(--font-display);
            z-index: 1;
        }

        .btn-glow::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, #0057FF 0%, #00F0FF 100%);
            z-index: -1;
            transition: opacity 0.3s ease;
            opacity: 0;
        }

        .btn-glow:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(0, 240, 255, 0.5);
        }

        .btn-glow:hover::before {
            opacity: 1;
        }

        .btn-outline-glass {
            color: var(--text-main);
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
            font-family: var(--font-display);
        }

        .btn-outline-glass:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero-section {
            flex: 1;
            position: relative;
            padding: 160px 0 100px;
            overflow: hidden;
        }

        /* Ambient Orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            animation: float 10s infinite ease-in-out alternate;
        }
        .orb-1 {
            top: -10%; left: -10%;
            width: 500px; height: 500px;
            background: rgba(0, 240, 255, 0.15);
        }
        .orb-2 {
            bottom: -20%; right: -10%;
            width: 600px; height: 600px;
            background: rgba(112, 0, 255, 0.15);
            animation-delay: -5s;
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 50px) scale(1.1); }
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .badge-premium {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #A1A1AA;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        .badge-premium .dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #00F0FF;
            box-shadow: 0 0 10px #00F0FF;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(0, 240, 255, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(0, 240, 255, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 240, 255, 0); }
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
        }

        .hero-title span {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.15rem;
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 2.5rem;
            max-width: 650px;
        }

        /* Mockup UI */
        .mockup-container {
            position: relative;
            perspective: 1000px;
            z-index: 2;
        }

        .mockup-window {
            background: var(--bg-surface);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 30px 60px -12px rgba(0,0,0,0.8), 0 0 0 1px rgba(255,255,255,0.05) inset;
            transform: rotateY(-15deg) rotateX(5deg) scale(0.95);
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .mockup-container:hover .mockup-window {
            transform: rotateY(0deg) rotateX(0deg) scale(1);
        }

        .mockup-header {
            background: rgba(0,0,0,0.3);
            padding: 12px 20px;
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            gap: 8px;
        }

        .mockup-header .circle {
            width: 12px; height: 12px; border-radius: 50%;
        }

        .mockup-body {
            padding: 30px;
            background-image: 
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .mockup-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            backdrop-filter: blur(5px);
        }

        .floating-badge {
            position: absolute;
            bottom: -20px; right: -20px;
            background: var(--secondary-gradient);
            color: white;
            padding: 12px 24px;
            border-radius: 100px;
            font-weight: 700;
            font-family: var(--font-display);
            box-shadow: 0 10px 20px rgba(255, 0, 122, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
            transform: translateZ(50px);
            animation: bounce 3s infinite ease-in-out;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0) translateZ(50px); }
            50% { transform: translateY(-10px) translateZ(50px); }
        }

        /* Features Section */
        .features-section {
            padding: 100px 0;
            background: linear-gradient(to bottom, var(--bg-color), #0a0a0c);
            position: relative;
            z-index: 1;
        }

        .section-header {
            text-align: center;
            margin-bottom: 80px;
        }
        
        .section-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .section-header p {
            color: var(--text-muted);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .feature-box {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 40px;
            height: 100%;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-box::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            background: var(--primary-gradient);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .feature-box:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-10px);
            box-shadow: 0 20px 40px -20px rgba(0,0,0,0.5);
        }

        .feature-box:hover::before {
            opacity: 1;
        }

        .feature-icon-wrapper {
            width: 60px; height: 60px;
            border-radius: 16px;
            background: rgba(0, 240, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            color: #00F0FF;
        }

        .feature-box.premium .feature-icon-wrapper {
            background: rgba(255, 0, 122, 0.1);
            color: #FF007A;
        }
        
        .feature-box.premium:hover::before {
            background: var(--secondary-gradient);
        }

        .feature-box h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .feature-box p {
            color: var(--text-muted);
            line-height: 1.6;
            margin: 0;
        }

        /* Banner CTA */
        .cta-banner {
            margin: 80px 0;
            background: var(--bg-surface);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 60px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-banner::after {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 100%; height: 100%;
            background: radial-gradient(circle at center, rgba(0,240,255,0.1) 0%, transparent 60%);
            pointer-events: none;
        }

        /* Footer */
        .site-footer {
            border-top: 1px solid var(--glass-border);
            padding: 40px 0;
            background: #000;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .site-footer a {
            color: #00F0FF;
            text-decoration: none;
            transition: color 0.2s;
        }

        .site-footer a:hover {
            color: #FFFFFF;
        }
    </style>
</head>
<body>

    <!-- Ambient Background -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <img src="{{ asset('logo_horizontal.svg') }}" alt="EDLibre" height="50">
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-3">
                    <li class="nav-item">
                        <a href="{{ url('/documentacion') }}" class="nav-link text-white opacity-75">Documentación</a>
                    </li>
                    <li class="nav-item">
                        <a href="#modelo" class="nav-link text-white opacity-75">El Modelo</a>
                    </li>
                    <li class="nav-item">
                        <a href="#contacto" class="nav-link text-white opacity-75">Soporte Premium</a>
                    </li>
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item ms-lg-3">
                                <a href="{{ url('/home') }}" class="btn-glow text-decoration-none d-inline-block">Ir al Panel</a>
                            </li>
                        @else
                            <li class="nav-item ms-lg-3">
                                <a href="{{ route('login') }}" class="btn-outline-glass text-decoration-none d-inline-block">Ingresar</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="btn-glow text-decoration-none d-inline-block">Registrarse</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 pe-lg-5">
                    <div class="badge-premium">
                        <span class="dot"></span> Solución Exclusiva Entidades Públicas 🇨🇴
                    </div>
                    
                    <h1 class="hero-title">
                        Evaluación LNR <br><span>Sin Restricciones</span>
                    </h1>
                    
                    <p class="hero-subtitle">
                        Ante la restricción de acceso al sistema <strong>EDL-APP</strong> de la CNSC (desde mayo de 2026), EDGASANC.COM presenta <strong>EDLibre</strong>: el sistema definitivo para evaluar los empleos de Libre Nombramiento y Remoción (Niveles Asesor, Profesional, Técnico y Asistencial).
                    </p>
                    
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        @auth
                            <a href="{{ url('/home') }}" class="btn-glow text-decoration-none">Acceder al Sistema</a>
                        @else
                            <a href="https://github.com/edgasanc85/EDLibre" target="_blank" class="btn-glow text-decoration-none">Descargar Ahora</a>
                            <a href="#modelo" class="btn-outline-glass text-decoration-none">Conocer el Modelo</a>
                        @endauth
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="mockup-container">
                        <div class="mockup-window">
                            <div class="mockup-header">
                                <div class="circle" style="background: #FF5F56;"></div>
                                <div class="circle" style="background: #FFBD2E;"></div>
                                <div class="circle" style="background: #27C93F;"></div>
                            </div>
                            <div class="mockup-body">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="m-0" style="font-size: 1.2rem;">Panel de Evaluación</h4>
                                    <span class="badge bg-success bg-opacity-25 text-success rounded-pill border border-success border-opacity-25 px-3 py-1">Vigencia 2026</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="mockup-card d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle bg-primary bg-opacity-25 d-flex align-items-center justify-content-center" style="width:40px; height:40px; color:#00F0FF;">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                                </div>
                                                <div>
                                                    <div style="font-weight:600; font-size:0.9rem;">Concertación de Compromisos</div>
                                                    <div style="font-size:0.75rem; color:var(--text-muted);">Nivel Asesor - Etapa Inicial</div>
                                                </div>
                                            </div>
                                            <div class="badge bg-primary bg-opacity-25 text-info border border-info border-opacity-25 rounded-pill">Aprobado</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mockup-card d-flex align-items-center justify-content-between" style="border-color: rgba(255, 0, 122, 0.3);">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:40px; height:40px; background: rgba(255, 0, 122, 0.1); color:#FF007A;">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                </div>
                                                <div>
                                                    <div style="font-weight:600; font-size:0.9rem;">Evaluación Definitiva</div>
                                                    <div style="font-size:0.75rem; color:var(--text-muted);">Pendiente de calificación</div>
                                                </div>
                                            </div>
                                            <div class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 rounded-pill">En Proceso</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="floating-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            GPLv3 License
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Features / Business Model -->
    <section id="modelo" class="features-section">
        <div class="container">
            <div class="section-header">
                <h2>El Modelo EDLibre</h2>
                <p>Nuestra filosofía es similar a la de grandes proyectos como Moodle. Democratizamos el acceso a la tecnología para el sector público colombiano mediante un modelo sostenible y transparente.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="feature-box">
                        <div class="feature-icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"></path><path d="M9 18c-4.51 2-5-2-7-2"></path></svg>
                        </div>
                        <h3>100% Libre y Gratuito</h3>
                        <p>El código fuente del sistema está licenciado bajo GPLv3. Cualquier entidad puede descargar el repositorio, alojarlo en su propia infraestructura y usarlo libremente sin pagar licencias por usuario o anualidades.</p>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="feature-box">
                        <div class="feature-icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        </div>
                        <h3>Cumplimiento LNR</h3>
                        <p>Diseñado específicamente para cubrir el vacío dejado por la restricción de la CNSC. Soporta nativamente el proceso de evaluación para empleos de los niveles Asesor, Profesional, Técnico y Asistencial.</p>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="feature-box premium">
                        <div class="feature-icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                        </div>
                        <h3>Soporte Premium (Opcional)</h3>
                        <p>¿No cuenta con personal técnico? <span class="text-white fw-bold">EDGASANC.COM</span> ofrece servicios pagos de despliegue, configuración en servidores institucionales, soporte extendido y personalización a la medida.</p>
                    </div>
                </div>
            </div>

            <div id="contacto" class="cta-banner mt-5 text-start">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-3 py-2 mb-3 fw-bold" style="border: 1px solid rgba(25, 135, 84, 0.3);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            100% Libre y Sin Costos de Licencia
                        </span>
                        <h2 class="mb-3" style="font-family: var(--font-display); font-weight: 700;">Garantice la continuidad de sus procesos de EDL</h2>
                        <p class="text-muted fs-5 mb-3">
                            <strong>EDLibre</strong> es un proyecto de código abierto. Esto significa que su entidad pública puede descargar, instalar y utilizar el software de manera <strong>completamente gratuita</strong>. No generamos cobros por licencias, ni por cantidad de usuarios, ni cobros anuales encubiertos.
                        </p>
                        <p class="text-muted fs-6 mb-0">
                            <strong>¿Su entidad requiere acompañamiento?</strong> Si no cuenta con personal técnico especializado, <span class="text-white">EDGASANC.COM</span> ofrece, de manera <em>opcional</em>, sus servicios profesionales de consultoría para realizar la configuración inicial y el despliegue a la medida en sus servidores institucionales.
                        </p>
                    </div>
                    <div class="col-lg-5 text-center text-lg-end">
                        <div class="d-flex flex-column gap-3 align-items-center align-items-lg-end">
                            <a href="https://github.com/edgasanc85/EDLibre" target="_blank" class="btn-glow text-decoration-none d-flex align-items-center justify-content-center gap-2 w-100" style="max-width: 320px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>
                                Descargar Repositorio Gratis
                            </a>
                            <a href="mailto:gerencia@edgasanc.com" class="btn-outline-glass text-decoration-none d-flex align-items-center justify-content-center gap-2 w-100" style="max-width: 320px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                Cotizar Servicio de Despliegue
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer mt-auto">
        <div class="container text-center">
            <p class="mb-2">© {{ date('Y') }} <strong>EDGASANC.COM</strong>. Proyecto Open Source (GPLv3).</p>
            <p class="mb-0">
                Soporte y Consultoría: <a href="mailto:gerencia@edgasanc.com" class="fw-semibold">gerencia@edgasanc.com</a>
            </p>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    
    <!-- Smooth scroll for anchors -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
