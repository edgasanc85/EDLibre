<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Evaluación del Desempeño Laboral') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS (Bootstrap 5) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <!-- Vite CSS -->
    @vite(['resources/sass/app.scss'])

    <style>
        :root {
            --ea-bg: #0B0F19;
            --ea-primary: #6366F1;
            --ea-text: #E2E8F0;
            --ea-navbar-bg: rgba(11, 15, 25, 0.8);
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--ea-bg);
            color: var(--ea-text);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Glassmorphism Navbar */
        .navbar-custom {
            background-color: var(--ea-navbar-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .btn-primary-custom {
            background-color: var(--ea-primary);
            border-color: var(--ea-primary);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            background-color: #4f46e5;
            border-color: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .btn-outline-custom {
            color: var(--ea-text);
            border-color: rgba(255, 255, 255, 0.2);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .hero-section {
            flex: 1;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 80px 0;
        }

        /* Background glowing elements */
        .glow-circle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, rgba(99,102,241,0) 70%);
            z-index: 0;
        }
        .glow-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; }
        .glow-2 { bottom: -20%; right: -10%; width: 60vw; height: 60vw; }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, #ffffff, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: #94a3b8;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1rem;
            padding: 2rem;
            height: 100%;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(99, 102, 241, 0.3);
        }

        .feature-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(99, 102, 241, 0.1);
            color: var(--ea-primary);
            margin-bottom: 1.5rem;
        }

        .footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem 0;
            background-color: rgba(0, 0, 0, 0.2);
            color: #94a3b8;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                {{ config('app.name', 'EDL') }}
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-2">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/home') }}" class="btn btn-primary-custom px-4 rounded-pill">Ir al Panel</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="btn btn-outline-custom px-4 rounded-pill">Iniciar Sesión</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="btn btn-primary-custom px-4 rounded-pill">Registrarse</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="hero-section mt-5 pt-5">
        <div class="glow-circle glow-1"></div>
        <div class="glow-circle glow-2"></div>
        
        <div class="container hero-content">
            <div class="row align-items-center min-vh-75 py-5">
                <div class="col-lg-7 mb-5 mb-lg-0 pe-lg-5">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-4" style="background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.2);">
                        <span class="badge bg-primary rounded-pill">Nuevo</span>
                        <span style="color: #a5b4fc; font-size: 0.9rem; font-weight: 500;">Plataforma Oficial Nivel Asesor</span>
                    </div>
                    
                    <h1 class="hero-title">Evaluación del Desempeño Laboral</h1>
                    
                    <p class="hero-subtitle">
                        Este sistema permitirá gestionar la <strong>evaluación del desempeño laboral para el nivel asesor</strong> en entidades públicas de Colombia. Diseñado para simplificar y automatizar el proceso de seguimiento y calificación de los funcionarios de este nivel.
                    </p>
                    
                    <div class="d-flex flex-column flex-sm-row gap-3 mt-4">
                        @auth
                            <a href="{{ url('/home') }}" class="btn btn-primary-custom btn-lg rounded-pill px-5">Acceder al Sistema</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary-custom btn-lg rounded-pill px-5">Ingresar al Sistema</a>
                            <a href="#about" class="btn btn-outline-custom btn-lg rounded-pill px-5">Conocer Más</a>
                        @endauth
                    </div>
                </div>
                
                <div class="col-lg-5">
                    <div class="position-relative">
                        <!-- Decorative mockup -->
                        <div style="background: rgba(22, 22, 21, 0.8); border: 1px solid rgba(255,255,255,0.1); border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); overflow: hidden; backdrop-filter: blur(10px);">
                            <div class="px-3 py-2 border-bottom" style="border-color: rgba(255,255,255,0.1) !important; background: rgba(0,0,0,0.2);">
                                <div class="d-flex gap-2">
                                    <div class="rounded-circle" style="width: 10px; height: 10px; background: #ef4444;"></div>
                                    <div class="rounded-circle" style="width: 10px; height: 10px; background: #eab308;"></div>
                                    <div class="rounded-circle" style="width: 10px; height: 10px; background: #22c55e;"></div>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="d-flex gap-3 mb-4">
                                    <div class="rounded bg-primary bg-opacity-25 p-3 flex-fill" style="height: 80px;"></div>
                                    <div class="rounded bg-secondary bg-opacity-25 p-3 flex-fill" style="height: 80px;"></div>
                                </div>
                                <div class="rounded bg-secondary bg-opacity-10 w-100 mb-3" style="height: 15px;"></div>
                                <div class="rounded bg-secondary bg-opacity-10 w-75 mb-3" style="height: 15px;"></div>
                                <div class="rounded bg-secondary bg-opacity-10 w-100 mb-4" style="height: 15px;"></div>
                                
                                <div class="d-flex justify-content-between align-items-end mt-5">
                                    <div class="rounded bg-primary w-25" style="height: 40px;"></div>
                                    <div class="rounded bg-primary bg-opacity-50 w-25" style="height: 80px;"></div>
                                    <div class="rounded bg-primary bg-opacity-75 w-25" style="height: 60px;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Floating badge -->
                        <div class="position-absolute bg-white text-dark rounded-pill py-2 px-4 shadow-lg d-flex align-items-center gap-2" style="bottom: -20px; right: -20px; font-weight: 600;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            100% Open Source
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Features Section -->
            <div id="about" class="row mt-5 pt-5 g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"></path><path d="M9 18c-4.51 2-5-2-7-2"></path></svg>
                        </div>
                        <h4 class="mb-3 text-white">Open Source</h4>
                        <p class="mb-0 text-secondary">
                            Este software es de código abierto. Cualquier entidad pública interesada puede descargar y desplegar el sistema desde GitHub completamente gratis.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                        <h4 class="mb-3 text-white">Nivel Asesor</h4>
                        <p class="mb-0 text-secondary">
                            Módulo estructurado específicamente para funcionarios del nivel asesor en entidades públicas, cumpliendo con la normatividad vigente en Colombia.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card border-primary border-opacity-50 bg-primary bg-opacity-10">
                        <div class="feature-icon bg-primary text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                        </div>
                        <h4 class="mb-3 text-white">Soporte Técnico</h4>
                        <p class="mb-0 text-secondary">
                            En caso de requerir acompañamiento para implementación o adaptación, <strong class="text-white">EDGASANC.COM</strong> ofrece servicios de soporte técnico especializado.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto">
        <div class="container text-center">
            <p class="mb-2">© {{ date('Y') }} <strong>EDGASANC.COM</strong>. Todos los derechos reservados.</p>
            <p class="mb-0">
                ¿Necesita ayuda con la implementación? Contáctenos: 
                <a href="mailto:gerencia@edgasanc.com" class="text-primary text-decoration-none fw-semibold">gerencia@edgasanc.com</a>
            </p>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
