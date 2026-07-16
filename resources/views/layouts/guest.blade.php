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
            --ea-primary: #2f6fa5;
            --ea-secondary: #209f91;
            --ea-text-primary: #F8F9FA;
            --ea-border: #1E293B;
        }
        
        body {
            background-color: var(--ea-bg);
            color: var(--ea-text-primary);
            font-family: 'Nunito Sans', sans-serif;
            margin: 0;
            overflow-x: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .guest-container {
            width: 100%;
            max-width: 450px;
            padding: 15px;
        }

        .card {
            background-color: var(--ea-surface);
            border: 1px solid var(--ea-border);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5), 0 2px 4px -1px rgba(0, 0, 0, 0.3);
        }
        
        .card-header {
            border-bottom: 1px solid var(--ea-border);
            background-color: transparent;
            color: var(--ea-text-primary);
            font-weight: 700;
            text-align: center;
            font-size: 1.25rem;
            padding: 1.5rem 1.5rem 1rem 1.5rem;
        }

        .card-body {
            color: var(--ea-text-primary);
            padding: 2rem;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--ea-border);
            color: var(--ea-text-primary);
        }
        
        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: var(--ea-primary);
            color: var(--ea-text-primary);
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--ea-primary) 0%, var(--ea-secondary) 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #0891b2 100%);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
            transform: translateY(-2px);
        }

        .text-muted {
            color: #9CA3AF !important;
        }
        
        .form-label {
            color: #D1D5DB;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    
    <div class="guest-container">
        <div class="text-center mb-4">
            <a href="{{ url('/') }}" class="text-decoration-none">
                <img src="{{ asset('logo_horizontal.svg') }}" alt="{{ config('app.name', 'Laravel') }}" height="60" class="mb-3">
            </a>
        </div>
        
        @yield('content')
        {{ $slot ?? '' }}
        
        <div class="text-center mt-4">
            <p class="text-muted small mb-0">Derechos Reservados {{ date('Y') }}</p>
            <p class="text-muted small">Con la tecnología de <a href="https://edgasanc.com" class="text-decoration-none fw-bold" style="color: var(--ea-primary);" target="_blank">EDGASANC.COM</a></p>
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
