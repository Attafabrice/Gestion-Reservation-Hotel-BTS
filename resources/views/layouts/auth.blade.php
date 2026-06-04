<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Grandoria')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @stack('styles')

    <style>
        body {
            background-color: #f4f6f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .auth-navbar {
            background-color: #1e2a38;
        }

        .auth-navbar .navbar-brand span {
            font-size: 1.2rem;
            font-weight: 600;
            color: #fff;
        }

        .auth-navbar .logo-img {
            max-width: 36px;
            height: auto;
        }

        .auth-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 40px 0;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .card-body {
            padding: 2rem;
        }

        .auth-footer {
            background-color: #fff;
            border-top: 1px solid #e0e0e0;
            padding: 12px 24px;
            font-size: 0.85rem;
            color: #6c757d;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Navbar légère -->
    <nav class="navbar auth-navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2"
               href="{{ route('client.accueil') }}">
                <img src="{{ asset('img/logo-hotel.png') }}" alt="logo" class="logo-img">
                <span>Grandoria</span>
            </a>
            <a href="{{ route('client.accueil') }}"
               class="btn btn-sm btn-outline-light">
                <i class="bi bi-arrow-left"></i> Retour au site
            </a>
        </div>
    </nav>

    <!-- Alertes -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Contenu -->
    <div class="auth-wrapper">
        <div class="container">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="auth-footer">
        © {{ date('Y') }} Grandoria. Tous droits réservés.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>