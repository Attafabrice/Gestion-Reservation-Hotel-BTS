<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Grandoria — Admin')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @stack('styles')
    <style>
        :root {
            --sidebar-w: 250px;
            --topbar-h: 56px;
            --sidebar-bg: #1e2a38;
        }
        body { background-color: #f4f6f9; }

        /* ── SIDEBAR ── */
        .sidebar {
        width: var(--sidebar-w);
        height: 100vh;           /* ✅ hauteur fixe = viewport */
        background: var(--sidebar-bg);
        position: fixed; top:0; left:0;
        z-index: 1050;
        display: flex; flex-direction: column;
        transition: transform .3s ease;
        overflow: hidden;        /* ✅ le scroll sera sur nav, pas ici */
        }
        .sidebar nav {
            flex: 1;                 /* ✅ prend tout l'espace dispo */
            overflow-y: auto;        /* ✅ scroll uniquement sur le menu */
            overflow-x: hidden;
        }
        /* Scrollbar discrète */
        .sidebar nav::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar nav::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,.2);
            border-radius: 4px;
        }
        .sidebar nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,.4);
        }
        .sidebar-brand { padding:20px 15px; border-bottom:1px solid rgba(255,255,255,.1); }
        .sidebar-brand img { max-width:40px; }
        .sidebar-brand .brand-name { font-size:1rem; font-weight:600; color:#fff; }
        .sidebar .nav-link {
            color:rgba(255,255,255,.75); padding:10px 20px;
            border-radius:6px; margin:2px 10px;
            transition:all .2s ease; font-size:.9rem;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background:rgba(255,255,255,.12); color:#fff;
        }
        .sidebar .nav-link i { width:20px; margin-right:8px; }
        .sidebar-section-title {
            font-size:.7rem; text-transform:uppercase;
            letter-spacing:1px; color:rgba(255,255,255,.35); padding:12px 20px 4px;
        }

        /* ── TOPBAR ── */
        .topbar {
            margin-left: var(--sidebar-w);
            background:#fff; border-bottom:1px solid #e0e0e0;
            padding:0 24px; height:var(--topbar-h);
            display:flex; align-items:center; justify-content:space-between;
            position:sticky; top:0; z-index:999;
            transition: margin-left .3s ease;
        }
        .sidebar-toggle-btn {
            display:none; background:none; border:none;
            font-size:1.4rem; color:#495057; cursor:pointer;
            padding:4px 8px; border-radius:6px; line-height:1;
        }
        .sidebar-toggle-btn:hover { background:#f0f0f0; }

        /* ── CONTENT ── */
        .admin-content {
            margin-left: var(--sidebar-w);
            padding:24px;
            min-height: calc(100vh - var(--topbar-h) - 44px);
            transition: margin-left .3s ease;
        }
        .admin-footer {
            margin-left: var(--sidebar-w);
            background:#fff; border-top:1px solid #e0e0e0;
            padding:12px 24px; font-size:.85rem; color:#6c757d;
            text-align:center; transition: margin-left .3s ease;
        }

        /* ── OVERLAY ── */
        .sidebar-overlay {
            display:none; position:fixed; inset:0;
            background:rgba(0,0,0,.5); z-index:1040;
        }
        body.sidebar-open .sidebar-overlay { display:block; }

        /* ══════════════════════════════
           TABLETTE  768px – 991px
           Sidebar icon-only (64px)
        ══════════════════════════════ */
        @media (max-width:991px) and (min-width:768px) {
            :root { --sidebar-w: 64px; }
            .sidebar-brand .brand-name,
            .sidebar .nav-link .link-label,
            .sidebar-section-title { display:none; }
            .sidebar .nav-link {
                padding:12px; margin:2px 6px;
                display:flex; justify-content:center; align-items:center;
            }
            .sidebar .nav-link i { margin-right:0; font-size:1.2rem; width:auto; }
            .sidebar-brand { justify-content:center; padding:16px 8px; }
            .sidebar-toggle-btn { display:block; }
            /* Tooltip texte au survol */
            .sidebar .nav-link { position:relative; }
            .sidebar .nav-link:hover::after {
                content: attr(data-label);
                position:absolute; left:72px; top:50%;
                transform:translateY(-50%);
                background:#1e2a38; color:#fff;
                padding:4px 10px; border-radius:6px;
                white-space:nowrap; font-size:.85rem;
                z-index:2000; pointer-events:none;
                box-shadow:0 2px 8px rgba(0,0,0,.3);
            }
        }

        /* ══════════════════════════════
           MOBILE  < 768px
           Sidebar cachée, overlay
        ══════════════════════════════ */
        @media (max-width:767px) {
            .sidebar { transform:translateX(-100%); width:250px; }
            body.sidebar-open .sidebar { transform:translateX(0); }
            .topbar, .admin-content, .admin-footer { margin-left:0 !important; }
            .sidebar-toggle-btn { display:block; }
            .admin-content { padding:16px 12px; }
        }

        /* ══════════════════════════════
           XS  < 480px
        ══════════════════════════════ */
        @media (max-width:479px) {
            .topbar { padding:0 12px; }
            .topbar .user-name { display:none; }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="adminSidebar">
    <div class="sidebar-brand d-flex align-items-center gap-2">
        <img src="{{ asset('img/logo-hotel.png') }}" alt="logo">
        <span class="brand-name">Grandoria</span>
    </div>
    <nav class="mt-3 flex-grow-1">
        <p class="sidebar-section-title">Principal</p>
        <a href="{{ route('admin.dashboard') }}" data-label="Dashboard"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i><span class="link-label"> Dashboard</span>
        </a>

        <p class="sidebar-section-title">Gestion</p>
        <a href="{{ route('admin.reservations.index') }}" data-label="Réservations"
           class="nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i><span class="link-label"> Réservations</span>
        </a>
        <a href="{{ route('admin.chambres.index') }}" data-label="Chambres"
           class="nav-link {{ request()->routeIs('admin.chambres.*') ? 'active' : '' }}">
            <i class="bi bi-door-open"></i><span class="link-label"> Chambres</span>
        </a>
        <a href="{{ route('admin.users.index') }}" data-label="Utilisateurs"
           class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i><span class="link-label"> Cients</span>
        </a>
        <a href="{{ route('admin.paiements.index') }}" data-label="Paiements"
           class="nav-link {{ request()->routeIs('admin.paiements.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i><span class="link-label"> Paiements</span>
        </a>
        <a href="{{ route('admin.contacts.index') }}" data-label="Messages"
           class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
            <i class="bi bi-envelope"></i><span class="link-label"> Messages</span>
        </a>

        <p class="sidebar-section-title">Configuration</p>
        <a href="{{ route('admin.type_chambres.index') }}" data-label="Types chambres"
           class="nav-link {{ request()->routeIs('admin.type_chambres.*') ? 'active' : '' }}">
            <i class="bi bi-tag"></i><span class="link-label"> Types de chambres</span>
        </a>
        <a href="{{ route('admin.types_reservation.index') }}" data-label="Types réservation"
           class="nav-link {{ request()->routeIs('admin.types_reservation.*') ? 'active' : '' }}">
            <i class="bi bi-bookmark"></i><span class="link-label"> Types de réservation</span>
        </a>
        <a href="{{ route('admin.tarifs.index') }}" data-label="Tarifs"
           class="nav-link {{ request()->routeIs('admin.tarifs.*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i><span class="link-label"> Tarifs</span>
        </a>
        <p class="sidebar-section-title">Administration</p>
        <a href="{{ route('admin.roles.index') }}" data-label="Rôles"
        class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock"></i><span class="link-label"> Rôles</span>
        </a>
        <a href="{{ route('admin.users.admins') }}" data-label="Administrateurs"
        class="nav-link {{ request()->routeIs('admin.users.admins') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i><span class="link-label"> Administrateurs</span>
        </a>
    </nav>
    <div class="p-3 border-top border-secondary flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-100 text-start text-danger border-0 bg-transparent">
                <i class="bi bi-box-arrow-right"></i><span class="link-label"> Déconnexion</span>
            </button>
        </form>
    </div>
</aside>

<!-- TOPBAR -->
<div class="topbar">
    <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="Menu">
        <i class="bi bi-list"></i>
    </button>

    <div class="ms-auto d-flex align-items-center gap-3">

        {{--  CLOCHE NOTIFICATIONS --}}
        <div class="dropdown">
            <button class="btn btn-sm position-relative p-1 border-0 bg-transparent"
                    data-bs-toggle="dropdown" aria-expanded="false"
                    style="color:#495057">
                <i class="bi bi-bell-fill fs-5"></i>
                @if($totalNotifications > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                          style="font-size:.6rem">
                        {{ $totalNotifications > 99 ? '99+' : $totalNotifications }}
                    </span>
                @endif
            </button>

            <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0"
                 style="width:360px;max-height:480px;overflow-y:auto">

                {{-- Header --}}
                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom bg-light">
                    <span class="fw-semibold">Notifications</span>
                    @if($totalNotifications > 0)
                        <span class="badge bg-danger rounded-pill">{{ $totalNotifications }}</span>
                    @endif
                </div>

                {{-- Réservations en attente --}}
                @if($reservationsEnAttente->count() > 0)
                    <div class="px-3 py-2 border-bottom">
                        <small class="text-muted text-uppercase fw-semibold"
                               style="font-size:.7rem;letter-spacing:.5px">
                            Réservations en attente
                        </small>
                    </div>
                    @foreach($reservationsEnAttente as $res)
                        <a href="{{ route('admin.reservations.show', $res->id) }}"
                           class="dropdown-item d-flex align-items-start gap-2 py-2 border-bottom">
                            <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:34px;height:34px">
                                <i class="bi bi-calendar-check text-white" style="font-size:.85rem"></i>
                            </div>
                            <div class="lh-sm">
                                <div class="fw-semibold" style="font-size:.875rem">
                                    {{ $res->user->nom ?? 'Client' }}
                                    {{ $res->user->prenom ?? '' }}
                                </div>
                                <div class="text-muted" style="font-size:.8rem">
                                    Chambre {{ $res->chambre->numero ?? '—' }} ·
                                    {{ $res->code_reservation }}
                                </div>
                                <div class="text-muted" style="font-size:.75rem">
                                    {{ $res->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif

                {{-- Messages non lus --}}
                @if($messagesNonLus->count() > 0)
                    <div class="px-3 py-2 border-bottom">
                        <small class="text-muted text-uppercase fw-semibold"
                               style="font-size:.7rem;letter-spacing:.5px">
                            Messages non lus
                        </small>
                    </div>
                    @foreach($messagesNonLus as $msg)
                        <a href="{{ route('admin.contacts.show', $msg->id) }}"
                           class="dropdown-item d-flex align-items-start gap-2 py-2 border-bottom">
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:34px;height:34px">
                                <i class="bi bi-envelope text-white" style="font-size:.85rem"></i>
                            </div>
                            <div class="lh-sm">
                                <div class="fw-semibold" style="font-size:.875rem">
                                    {{ $msg->nom ?? 'Visiteur' }}
                                </div>
                                <div class="text-muted" style="font-size:.8rem">
                                    {{ Str::limit($msg->message ?? $msg->sujet ?? '—', 40) }}
                                </div>
                                <div class="text-muted" style="font-size:.75rem">
                                    {{ $msg->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif

                {{-- Aucune notification --}}
                @if($totalNotifications === 0)
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-bell-slash fs-4 d-block mb-1"></i>
                        Aucune notification
                    </div>
                @endif

                {{-- Footer --}}
                <div class="d-flex gap-2 p-2 border-top bg-light">
                    <a href="{{ route('admin.reservations.index') }}"
                       class="btn btn-sm btn-outline-warning flex-fill">
                        Voir réservations
                    </a>
                    <a href="{{ route('admin.contacts.index') }}"
                       class="btn btn-sm btn-outline-primary flex-fill">
                        Voir messages
                    </a>
                </div>
            </div>
        </div>

        {{-- Profil admin --}}
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center gap-2 text-dark text-decoration-none dropdown-toggle"
               data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-5"></i>
                <span class="user-name d-none d-sm-inline">
                    {{ auth()->user()->nom ?? auth()->user()->name ?? 'Admin' }}
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('password.edit') }}">
                        <i class="bi bi-key"></i> Changer le mot de passe
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('client.accueil') }}" target="_blank">
                        <i class="bi bi-globe"></i> Voir le site
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right"></i> Déconnexion
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    </div>
</div>

<!-- CONTENU -->
<main class="admin-content">
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
    @yield('admin-content')
</main>

<footer class="admin-footer">
    &copy; {{ date('Y') }} Grandoria &mdash; Espace Administration
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
<script>
(function(){
    const toggle  = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');
    const body    = document.body;
    function open()  { body.classList.add('sidebar-open');    toggle.querySelector('i').className='bi bi-x-lg'; }
    function close() { body.classList.remove('sidebar-open'); toggle.querySelector('i').className='bi bi-list'; }
    if(toggle)  toggle.addEventListener('click', ()=> body.classList.contains('sidebar-open') ? close() : open());
    if(overlay) overlay.addEventListener('click', close);
    document.addEventListener('keydown', e=> e.key==='Escape' && close());
    window.addEventListener('resize', ()=> window.innerWidth>=992 && close());
})();
</script>
@stack('scripts')
</body>
</html>