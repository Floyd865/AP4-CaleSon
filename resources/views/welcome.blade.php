<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Cale & Sons') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
                background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 50%, #0f1419 100%);
                min-height: 100vh;
                color: #e2e8f0;
            }

            /* Navigation */
            .navbar {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid rgba(59, 130, 246, 0.2);
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            }

            .navbar-container {
                max-width: 1400px;
                margin: 0 auto;
                padding: 1rem 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .logo-section {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .logo {
                width: 60px;
                height: auto;
                filter: drop-shadow(0 0 15px rgba(59, 130, 246, 0.5));
                border-radius: 12px;
                background: rgba(255, 255, 255, 0.1);
                padding: 8px;
            }

            .brand-name {
                font-size: 1.5rem;
                font-weight: 700;
                background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #2563eb 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .nav-links {
                display: flex;
                gap: 1rem;
                align-items: center;
            }

            .nav-link {
                color: #cbd5e1;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.3s ease;
                padding: 0.5rem 1rem;
            }

            .nav-link:hover {
                color: #60a5fa;
            }

            .btn {
                padding: 0.625rem 1.5rem;
                border-radius: 0.5rem;
                text-decoration: none;
                font-weight: 500;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
                font-size: 0.95rem;
                display: inline-block;
            }

            .btn-primary {
                background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            }

            .btn-primary:hover {
                background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
                box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
                transform: translateY(-2px);
            }

            .btn-secondary {
                background: rgba(255, 255, 255, 0.1);
                color: #e2e8f0;
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .btn-secondary:hover {
                background: rgba(255, 255, 255, 0.15);
                border-color: rgba(59, 130, 246, 0.5);
            }

            /* Container */
            .container {
                max-width: 1400px;
                margin: 0 auto;
                padding: 2rem;
            }

            /* Hero Section */
            .hero {
                text-align: center;
                padding: 3rem 0;
                margin-bottom: 2rem;
            }

            .hero h1 {
                font-size: 3rem;
                font-weight: 700;
                margin-bottom: 1rem;
                background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #f97316 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                line-height: 1.2;
            }

            .hero p {
                font-size: 1.1rem;
                color: #cbd5e1;
                margin-bottom: 2rem;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .navbar-container {
                    flex-direction: column;
                    gap: 1rem;
                }

                .nav-links {
                    flex-direction: column;
                    width: 100%;
                }

                .hero h1 {
                    font-size: 2rem;
                }
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar">
            <div class="navbar-container">
                <div class="logo-section">
                    <a href="/">
                        <img src="{{ asset('images/Logo_Cale_sons.png') }}" alt="Cale & Sons Logo" class="logo">
                    </a>
                    <h1 class="brand-name">Cale & Sons</h1>
                </div>

                <div class="nav-links">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            Tableau de bord
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-secondary">
                            Se connecter
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                Créer un compte
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="container">
            <div class="hero">
                <h1>Festival Cale & Sons</h1>
                <p>
                    Découvrez toutes nos manifestations : concerts, conférences, ateliers et expositions.
                </p>
            </div>

            <!-- Include Manifestations Index Content -->
            @include('manifestations.content', [
                'manifestations' => $manifestations,
                'filterOptions' => $filterOptions,
                'filters' => $filters
            ])
        </div>
    </body>
</html>