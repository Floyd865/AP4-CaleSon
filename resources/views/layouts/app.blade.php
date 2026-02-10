<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Cale & Sons') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
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
            }

            .brand-name {
                font-size: 1.5rem;
                font-weight: 700;
                background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #2563eb 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .nav-menu {
                display: flex;
                align-items: center;
                gap: 2rem;
            }

            .nav-link {
                color: #cbd5e1;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.3s ease;
                padding: 0.5rem 1rem;
                border-bottom: 2px solid transparent;
            }

            .nav-link:hover,
            .nav-link.active {
                color: #60a5fa;
                border-bottom-color: #60a5fa;
            }

            .user-menu {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .user-info {
                color: #cbd5e1;
                font-size: 0.9rem;
            }

            .btn {
                padding: 0.625rem 1.25rem;
                border-radius: 0.5rem;
                font-weight: 500;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
                font-size: 0.95rem;
                text-decoration: none;
                display: inline-block;
            }

            .btn-secondary {
                background: rgba(255, 255, 255, 0.05);
                color: #e2e8f0;
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .btn-secondary:hover {
                background: rgba(255, 255, 255, 0.08);
                border-color: rgba(59, 130, 246, 0.3);
            }

            /* Main Content */
            .main-content {
                max-width: 1400px;
                margin: 0 auto;
                padding: 3rem 2rem;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .navbar-container {
                    flex-direction: column;
                    gap: 1rem;
                }

                .nav-menu {
                    flex-direction: column;
                    width: 100%;
                    gap: 1rem;
                }

                .main-content {
                    padding: 1.5rem 1rem;
                }
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar">
            <div class="navbar-container">
                <div class="logo-section">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/Logo_Cale_sons.png') }}" alt="Cale & Sons Logo" class="logo">
                    </a>
                    <h1 class="brand-name">Cale & Sons</h1>
                </div>

                <div class="nav-menu">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Tableau de bord
                    </a>
                    
                    <a href="{{ route('manifestations.index') }}" class="nav-link {{ request()->routeIs('manifestations.*') ? 'active' : '' }}">
                        Manifestations
                    </a>
                    
                    <!-- User Menu -->
                    <div class="user-menu">
                        <span class="user-info">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-secondary">
                                Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="main-content">
            {{ $slot }}
        </main>
    </body>
</html>