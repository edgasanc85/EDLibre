<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @livewireStyles

    <style>
        :root {
            --ea-bg: #0B0F19;
            --ea-surface: #0F1423;
            --ea-primary: #6366F1;
            --ea-secondary: #06B6D4;
            --ea-text-primary: #F8F9FA;
            --ea-border: #1E293B;
            --ea-sidenav-bg: #0B0F19;
            --ea-sidenav-text-active: #ffffff;
        }
        
        body.no-transition {
            background-color: var(--ea-bg);
            color: var(--ea-text-primary);
            font-family: 'Nunito Sans', sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        #layoutSidenav {
            display: flex;
        }
        
        .card {
            background-color: var(--ea-surface);
            border: 1px solid var(--ea-border);
            color: var(--ea-text-primary);
        }
        
        .card-header {
            border-bottom: 1px solid var(--ea-border);
            background-color: transparent;
        }
        
        .topnav {
            background-color: var(--ea-surface);
            border-bottom: 1px solid var(--ea-border);
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidenav-container {
            width: 250px;
            background-color: var(--ea-sidenav-bg);
            border-right: 1px solid var(--ea-border);
            min-height: calc(100vh - 60px);
        }

        .main-content {
            flex-grow: 1;
            padding: 2rem;
            min-height: calc(100vh - 60px);
            display: flex;
            flex-direction: column;
            width: calc(100% - 250px);
            background-color: #F8F9FA;
            color: #1E293B;
        }

        .main-content-body {
            flex-grow: 1;
        }

        .main-footer {
            margin-top: auto;
            padding: 1rem 0;
            border-top: 1px solid var(--ea-border);
            text-align: center;
            font-size: 0.875rem;
            color: #9CA3AF;
        }

        .card {
            background-color: var(--ea-surface);
            border: 1px solid var(--ea-border);
        }
        
        .card-header {
            border-bottom: 1px solid var(--ea-border);
            background-color: rgba(255, 255, 255, 0.02);
            color: var(--ea-text-primary);
        }

        .card-body {
            color: var(--ea-text-primary);
        }

        .nav-link.text-white:hover {
            background-color: rgba(255,255,255,0.05);
            border-radius: 0.375rem;
        }
        
        /* Modifiers for elements within the Dark Theme */
        .text-muted {
            color: #9CA3AF !important;
        }
    </style>
</head>
<body class="no-transition">
    
    <!-- TOPNAV -->
    <header class="topnav sticky-top">
        <a class="navbar-brand fw-bold text-white text-decoration-none d-flex align-items-center" href="{{ url('/home') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        
        <div class="d-flex align-items-center">
            @guest
                @if (Route::has('login'))
                    <a class="nav-link text-white me-3" href="{{ route('login') }}">{{ __('Login') }}</a>
                @endif
                @if (Route::has('register'))
                    <a class="nav-link text-white" href="{{ route('register') }}">{{ __('Register') }}</a>
                @endif
            @else
                <div class="dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle text-white text-decoration-none" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark shadow" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i>{{ __('Logout') }}
                            </a>
                        </li>
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            @endguest
        </div>
    </header>

    <div id="layoutSidenav">
        
        <!-- SIDENAV -->
        <aside class="sidenav-container" id="layoutSidenav_nav">
            <nav class="nav flex-column p-3 gap-2">
                <a class="nav-link text-white px-3 py-2" href="{{ url('/home') }}">
                    <i class="bi bi-house-door me-2" style="color: var(--ea-primary);"></i> Dashboard
                </a>
                <!-- Future Navigation Links Go Here -->
                @if(auth()->check() && auth()->user()->is_admin)
                    <a class="nav-link text-white px-3 py-2 {{ request()->routeIs('users') ? 'bg-primary bg-opacity-25 rounded' : '' }}" href="{{ route('users') }}">
                        <i class="bi bi-people me-2" style="color: var(--ea-primary);"></i> Usuarios
                    </a>
                    <a class="nav-link text-white px-3 py-2 {{ request()->routeIs('dependencias') ? 'bg-primary bg-opacity-25 rounded' : '' }}" href="{{ route('dependencias') }}">
                        <i class="bi bi-diagram-3 me-2" style="color: var(--ea-primary);"></i> Dependencias
                    </a>
                    <a class="nav-link text-white px-3 py-2 {{ request()->routeIs('niveles') ? 'bg-primary bg-opacity-25 rounded' : '' }}" href="{{ route('niveles') }}">
                        <i class="bi bi-layers me-2" style="color: var(--ea-primary);"></i> Niveles
                    </a>
                    <a class="nav-link text-white px-3 py-2 {{ request()->routeIs('competencias') ? 'bg-primary bg-opacity-25 rounded' : '' }}" href="{{ route('competencias') }}">
                        <i class="bi bi-star me-2" style="color: var(--ea-primary);"></i> Competencias
                    </a>
                    <a class="nav-link text-white px-3 py-2 {{ request()->routeIs('periodos') ? 'bg-primary bg-opacity-25 rounded' : '' }}" href="{{ route('periodos') }}">
                        <i class="bi bi-calendar-event me-2" style="color: var(--ea-primary);"></i> Periodos
                    </a>
                    <a class="nav-link text-white px-3 py-2 {{ request()->routeIs('evaluadores') ? 'bg-primary bg-opacity-25 rounded' : '' }}" href="{{ route('evaluadores') }}">
                        <i class="bi bi-person-badge me-2" style="color: var(--ea-primary);"></i> Evaluadores
                    </a>
                    <a class="nav-link text-white px-3 py-2 {{ request()->routeIs('evaluados') ? 'bg-primary bg-opacity-25 rounded' : '' }}" href="{{ route('evaluados') }}">
                        <i class="bi bi-person-lines-fill me-2" style="color: var(--ea-primary);"></i> Evaluados
                    </a>
                @endif
                
                @if(auth()->check() && auth()->user()->evaluados()->exists())
                    <a class="nav-link text-white px-3 py-2 {{ request()->routeIs('mis-compromisos') ? 'bg-primary bg-opacity-25 rounded' : '' }}" href="{{ route('mis-compromisos') }}">
                        <i class="bi bi-file-earmark-check me-2" style="color: var(--ea-primary);"></i> Mis Compromisos
                    </a>
                @endif

                @if(auth()->check() && auth()->user()->evaluadores()->exists())
                    <a class="nav-link text-white px-3 py-2 {{ request()->routeIs('mis-evaluados') ? 'bg-primary bg-opacity-25 rounded' : '' }}" href="{{ route('mis-evaluados') }}">
                        <i class="bi bi-people me-2" style="color: var(--ea-primary);"></i> Mis Evaluados
                    </a>
                @endif
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <div class="main-content-body">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
            
            <!-- FOOTER -->
            <footer class="main-footer d-flex flex-column align-items-center justify-content-center py-3">
                <div>
                    &copy; {{ date('Y') }} - Diseñado y desarrollado por <a href="https://edgasanc.com" target="_blank" class="text-decoration-none fw-bold" style="color: var(--ea-primary);">EDGASANC.COM</a>
                </div>
                <div class="small text-muted mt-1">
                    Software de código abierto distribuido bajo la <a href="#" data-bs-toggle="modal" data-bs-target="#licenseModal" class="text-decoration-none text-muted fw-bold border-bottom border-secondary">GNU General Public License v3.0</a>
                </div>
            </footer>
        </main>
    </div>

    <!-- Modal Licencia -->
    <div class="modal fade" id="licenseModal" tabindex="-1" aria-labelledby="licenseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content shadow-lg border-0 rounded-3">
                <div class="modal-header bg-light border-0 py-3 px-4 border-bottom">
                    <h5 class="modal-title fw-bold text-dark" id="licenseModalLabel"><i class="bi bi-file-earmark-text me-2" style="color: var(--ea-primary);"></i>Licencia del Software</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="font-size: 0.85rem; line-height: 1.6;">
                    {!! Illuminate\Support\Str::markdown(file_get_contents(base_path('LICENCE.md'))) !!}
                </div>
                <div class="modal-footer bg-light border-0 py-3 px-4 border-top">
                    <button type="button" class="btn btn-secondary rounded-2" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts

    <!-- Bootstrap Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Global Key Bindings -->
    <script>
        document.addEventListener('keydown', function(event) {
            // ESC key to close modals
            if (event.key === 'Escape') {
                const openModals = document.querySelectorAll('.modal.show');
                openModals.forEach(modal => {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                });
            }
            
            // Enter key to submit forms (except inside textareas)
            if (event.key === 'Enter' && event.target.tagName !== 'TEXTAREA' && event.target.tagName !== 'BUTTON') {
                const form = event.target.closest('form');
                if (form) {
                    event.preventDefault();
                    const submitBtn = form.querySelector('[type="submit"]');
                    if (submitBtn) {
                        submitBtn.click();
                    } else {
                        form.submit();
                    }
                }
            }
        });
    </script>
</body>
</html>
