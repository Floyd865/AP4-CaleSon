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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
                background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 50%, #0f1419 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .auth-container {
                width: 100%;
                max-width: 450px;
                padding: 1.5rem;
            }

            .auth-card {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(59, 130, 246, 0.2);
                border-radius: 1.5rem;
                padding: 2.5rem;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            }

            .logo-section {
                text-align: center;
                margin-bottom: 2rem;
            }

            .logo {
                width: 100px;
                height: auto;
                margin: 0 auto 1rem;
                filter: drop-shadow(0 0 20px rgba(59, 130, 246, 0.5));
            }

            .brand-name {
                font-size: 2rem;
                font-weight: 700;
                background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #2563eb 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 0.5rem;
            }

            .auth-subtitle {
                color: #94a3b8;
                font-size: 0.95rem;
            }

            /* Form Styles */
            label {
                display: block;
                color: #cbd5e1;
                font-weight: 500;
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
            }

            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="tel"] {
                width: 100%;
                padding: 0.75rem 1rem;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 0.5rem;
                color: #e2e8f0;
                font-size: 0.95rem;
                transition: all 0.3s ease;
            }

            input[type="text"]:focus,
            input[type="email"]:focus,
            input[type="password"]:focus,
            input[type="tel"]:focus {
                outline: none;
                border-color: #3b82f6;
                background: rgba(255, 255, 255, 0.08);
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            input::placeholder {
                color: #64748b;
            }

            /* Checkbox */
            input[type="checkbox"] {
                width: 1.125rem;
                height: 1.125rem;
                border-radius: 0.25rem;
                border: 1px solid rgba(255, 255, 255, 0.2);
                background: rgba(255, 255, 255, 0.05);
            }

            input[type="checkbox"]:checked {
                background-color: #3b82f6;
                border-color: #3b82f6;
            }

            /* Buttons */
            .btn {
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                font-weight: 500;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
                font-size: 0.95rem;
                width: 100%;
                text-align: center;
                text-decoration: none;
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

            /* Links */
            a {
                color: #60a5fa;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            a:hover {
                color: #3b82f6;
                text-decoration: underline;
            }

            /* Error Messages */
            .error-message {
                color: #fca5a5;
                font-size: 0.875rem;
                margin-top: 0.5rem;
            }

            /* Success Messages */
            .success-message {
                background: rgba(34, 197, 94, 0.1);
                border: 1px solid rgba(34, 197, 94, 0.3);
                color: #86efac;
                padding: 0.75rem;
                border-radius: 0.5rem;
                margin-bottom: 1rem;
                font-size: 0.9rem;
            }

            /* Utility Classes */
            .mt-1 { margin-top: 0.25rem; }
            .mt-2 { margin-top: 0.5rem; }
            .mt-4 { margin-top: 1rem; }
            .mb-4 { margin-bottom: 1rem; }
            .ms-2 { margin-left: 0.5rem; }
            .ms-3 { margin-left: 0.75rem; }
            .ms-4 { margin-left: 1rem; }
            .block { display: block; }
            .inline-flex { display: inline-flex; }
            .flex { display: flex; }
            .items-center { align-items: center; }
            .justify-end { justify-content: flex-end; }
            .justify-between { justify-content: space-between; }
            .w-full { width: 100%; }
            .text-sm { font-size: 0.875rem; }
            .text-gray-600 { color: #94a3b8; }
            .underline { text-decoration: underline; }
            .rounded { border-radius: 0.25rem; }
            .rounded-md { border-radius: 0.375rem; }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="auth-card">
                <!-- Logo -->
                <div class="logo-section">
                    <a href="/">
                        <img src="{{ asset('images/Logo_Cale_sons.png') }}" alt="Cale & Sons Logo" class="logo">
                    </a>
                    <h1 class="brand-name">Cale & Sons</h1>
                </div>

                <!-- Content -->
                {{ $slot }}
            </div>
        </div>
    </body>
</html>