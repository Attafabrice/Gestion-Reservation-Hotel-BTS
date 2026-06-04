<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Grandoria')</title>

    <!-- Vendor CSS Files (locaux) -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        /* Styles pour le menu hamburger client */
        :root {
            --header-bg: #436e62;
            --nav-mobile-bg: #345b50;
        }
        
        .header {
            background: var(--header-bg);
            padding: 10px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo img {
            height: 50px;
        }
        
        /* Bouton hamburger */
        .mobile-nav-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 28px;
            color: white;
            cursor: pointer;
            z-index: 1001;
        }
        
        /* Menu desktop */
        .navmenu {
            display: flex;
        }
        
        .navmenu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 20px;
            align-items: center;
        }
        
        .navmenu a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            padding: 8px 0;
            transition: 0.3s;
        }
        
        .navmenu a:hover,
        .navmenu a.active {
            color: white;
        }
        
        /* Dropdown desktop (seulement pour Mon compte) */
        .navmenu .dropdown {
            position: relative;
        }
        
        .navmenu .dropdown ul {
            position: absolute;
            top: 100%;
            right: 0;
            left: auto;
            background: var(--nav-mobile-bg);
            flex-direction: column;
            gap: 0;
            min-width: 200px;
            border-radius: 6px;
            opacity: 0;
            visibility: hidden;
            transition: 0.3s;
            padding: 10px 0;
            z-index: 1002;
        }
        
        .navmenu .dropdown:hover ul {
            opacity: 1;
            visibility: visible;
        }
        
        .navmenu .dropdown ul li {
            width: 100%;
        }
        
        .navmenu .dropdown ul a {
            padding: 8px 20px;
            display: block;
            white-space: nowrap;
        }
        
        .navmenu .dropdown ul button {
            white-space: nowrap;
        }
        
        /* ==================== MOBILE ==================== */
        @media (max-width: 991px) {
            .mobile-nav-toggle {
                display: block;
            }
            
            .navmenu {
                position: fixed;
                top: 0;
                right: -100%;
                width: 280px;
                max-width: 80vw;
                height: 100vh;
                background: var(--nav-mobile-bg);
                transition: 0.3s ease-in-out;
                z-index: 1000;
                padding: 80px 20px 30px;
                overflow-y: auto;
            }
            
            .navmenu.active {
                right: 0;
            }
            
            .navmenu ul {
                flex-direction: column;
                gap: 0;
            }
            
            .navmenu li {
                width: 100%;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            
            .navmenu a {
                padding: 12px 0;
                display: block;
            }
            
            /* Dropdown mobile - uniquement pour Mon compte */
            .navmenu .dropdown ul {
                position: static;
                opacity: 1;
                visibility: visible;
                display: none;
                background: rgba(0,0,0,0.2);
                padding-left: 15px;
                margin-top: 5px;
                min-width: auto;
                box-shadow: none;
            }
            
            .navmenu .dropdown.open ul {
                display: block;
            }
            
            .navmenu .dropdown > a::after {
                content: " ▼";
                font-size: 10px;
            }
            
            .navmenu .dropdown.open > a::after {
                content: " ▲";
            }
            
            /* Overlay */
            body.menu-open {
                overflow: hidden;
            }
            
            body.menu-open::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>

<!-- HEADER -->
<header id="header" class="header">
    <div class="container">
        <a href="{{ route('client.accueil') }}" class="logo">
            <img src="{{ asset('img/logo-hotel.png') }}" alt="logo-hotel">
        </a>
        
        <button class="mobile-nav-toggle" id="mobileNavToggle">
            <i class="bi bi-list"></i>
        </button>
        
        <nav class="navmenu" id="navmenu">
            <ul>
                <li><a href="{{ route('client.accueil') }}" class="{{ request()->routeIs('client.accueil') ? 'active' : '' }}">Accueil</a></li>
                <li><a href="{{ route('client.about') }}" class="{{ request()->routeIs('client.about') ? 'active' : '' }}">A propos</a></li>
                <li><a href="{{ route('client.gallery') }}" class="{{ request()->routeIs('client.gallery') ? 'active' : '' }}">Nos chambres</a></li>
                <li><a href="{{ route('client.contact') }}" class="{{ request()->routeIs('client.contact') ? 'active' : '' }}">Contact</a></li>
                
                @guest
                    <li><a href="{{ route('register') }}">Inscription</a></li>
                    <li><a href="{{ route('login', ['redirect'=> url()->current()]) }}">Connexion</a></li>
                @endguest
                
                @auth
                    @if(!auth()->user()->isAdmin())
                        <li><a href="{{ route('client.reservation.index') }}" class="{{ request()->routeIs('client.reservation.*') ? 'active' : '' }}">Mes réservations</a></li>
                    @endif
                    
                    <li class="dropdown">
                        <a href="#">{{ auth()->user()->nom ?? auth()->user()->name ?? 'Mon compte' }}</a>
                        <ul>
                            <li><a href="{{ route('password.edit') }}">Changer mot de passe</a></li>
                            @if(auth()->user()->isAdmin())
                                <li><a href="{{ route('admin.dashboard') }}">Administration</a></li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" style="background:none; border:none; width:100%; text-align:left; padding:8px 20px; cursor:pointer;">Déconnexion</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </nav>
    </div>
</header>

<!-- ALERTES -->
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
    @if(session('info'))
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> {{ session('info') }}
        </div>
    @endif
</div>

<!-- CONTENU PRINCIPAL -->
<main>
    @yield('client-content')
</main>

<!-- FOOTER -->
<footer id="footer" class="footer dark-background">
    <div class="copyright">
        <div class="container d-flex flex-column flex-lg-row justify-content-center justify-content-lg-between align-items-center">
            <div class="d-flex flex-column align-items-center align-items-lg-start">
                <div>© Copyright <strong><span>Grandoria</span></strong>. Tous droits réservés</div>
                <div class="credits">Designed by <a href="DezignextAgency" target="_blank">DezignextAgency</a></div>
            </div>
            <div class="social-links order-first order-lg-last mb-3 mb-lg-0">
                <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>
    </div>
</footer>

<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/aos/aos.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation AOS
        if (typeof AOS !== 'undefined') AOS.init({ duration: 800, easing: 'slide', once: true });
        
        // MENU MOBILE
        const toggle = document.getElementById('mobileNavToggle');
        const navmenu = document.getElementById('navmenu');
        const body = document.body;
        
        function openMenu() {
            navmenu.classList.add('active');
            body.classList.add('menu-open');
            if (toggle) {
                const icon = toggle.querySelector('i');
                if (icon) icon.className = 'bi bi-x-lg';
            }
        }
        
        function closeMenu() {
            navmenu.classList.remove('active');
            body.classList.remove('menu-open');
            if (toggle) {
                const icon = toggle.querySelector('i');
                if (icon) icon.className = 'bi bi-list';
            }
        }
        
        if (toggle && navmenu) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                if (navmenu.classList.contains('active')) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });
        }
        
        // Fermer le menu en cliquant sur un lien (sauf les dropdowns "Mon compte")
        document.querySelectorAll('.navmenu a:not(.dropdown > a)').forEach(link => {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 991) {
                    setTimeout(closeMenu, 200);
                }
            });
        });
        
        // Gestion UNIQUEMENT du dropdown "Mon compte" sur mobile
        const dropdownToggles = document.querySelectorAll('.navmenu .dropdown > a');
        dropdownToggles.forEach(dropToggle => {
            dropToggle.addEventListener('click', function(e) {
                if (window.innerWidth <= 991) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    parent.classList.toggle('open');
                }
            });
        });
        
        // Fermer le menu avec Echap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navmenu.classList.contains('active')) {
                closeMenu();
            }
        });
        
        // Réinitialiser sur redimensionnement
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                if (navmenu.classList.contains('active')) {
                    closeMenu();
                }
                // Réinitialiser les dropdowns ouverts
                document.querySelectorAll('.navmenu .dropdown.open').forEach(drop => {
                    drop.classList.remove('open');
                });
            }
        });
    });
</script>

<script src="{{ asset('js/script.js') }}"></script>
@stack('scripts')
</body>
</html>