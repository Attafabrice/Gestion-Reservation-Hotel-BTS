@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('admin-content')

<h2 class="mb-1 fw-bold">Dashboard</h2>
<p class="text-muted mb-4">Bienvenue, {{ auth()->user()->nom ?? 'Admin' }} 👋</p>

{{-- ═══════════════════════════════════════ --}}
{{--         CARTES STATISTIQUES             --}}
{{-- ═══════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:#dbeafe">
                    <i class="bi bi-people-fill fs-4" style="color:#2563eb"></i>
                </div>
                <div>
                    <div class="text-muted small">Utilisateurs</div>
                    <div class="fs-4 fw-bold">{{ $totalUsers }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:#d1fae5">
                    <i class="bi bi-door-open-fill fs-4" style="color:#059669"></i>
                </div>
                <div>
                    <div class="text-muted small">Chambres</div>
                    <div class="fs-4 fw-bold">{{ $totalChambres }}</div>
                    <div class="small text-muted">
                        <span class="text-success">{{ $chambresDisponibles }} dispo</span>
                        · <span class="text-danger">{{ $chambresOccupees }} occupées</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:#fef3c7">
                    <i class="bi bi-calendar-check-fill fs-4" style="color:#f59e0b"></i>
                </div>
                <div>
                    <div class="text-muted small">Réservations</div>
                    <div class="fs-4 fw-bold">{{ $totalReservations }}</div>
                    @if($nbReservationsEnAttente > 0)
                        <div class="small">
                            <span class="badge bg-warning text-dark">
                                {{ $nbReservationsEnAttente }} en attente
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:#fee2e2">
                    <i class="bi bi-cash-stack fs-4" style="color:#dc2626"></i>
                </div>
                <div>
                    <div class="text-muted small">Revenus</div>
                    <div class="fs-4 fw-bold">{{ number_format($revenusTotal) }}</div>
                    <div class="text-muted small">FCFA</div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Alerte chambres presque complètes --}}
@if($chambresDisponibles <= 2)
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span>Attention : seulement <strong>{{ $chambresDisponibles }}</strong> chambre(s) disponible(s) !</span>
    </div>
@endif

{{-- ═══════════════════════════════════════ --}}
{{--            GRAPHIQUES                   --}}
{{-- ═══════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-bar-chart-fill text-primary me-2"></i>
                    Réservations par mois ({{ now()->year }})
                </h6>
                <canvas id="reservationsChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-graph-up-arrow text-success me-2"></i>
                    Revenus par mois ({{ now()->year }})
                </h6>
                <canvas id="revenusChart" height="120"></canvas>
            </div>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════ --}}
{{--       DERNIÈRES RÉSERVATIONS            --}}
{{-- ═══════════════════════════════════════ --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-semibold mb-0">
                <i class="bi bi-clock-history text-secondary me-2"></i>
                Dernières réservations
            </h6>
            <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-outline-primary">
                Voir tout
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Client</th>
                        <th>Chambre</th>
                        <th>Type</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Prix total</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dernieresReservations as $reservation)
                    @php
                        $statut = $reservation->statut;
                        $color  = match($statut) {
                            'en_attente' => 'warning',
                            'confirmee'  => 'success',
                            'annulee'    => 'danger',
                            'terminee'   => 'secondary',
                            default      => 'dark'
                        };
                    @endphp
                    <tr>
                        <td>
                            <span class="badge bg-secondary font-monospace" style="font-size:.75rem">
                                {{ $reservation->code_reservation ?? '—' }}
                            </span>
                        </td>
                        <td>{{ $reservation->user->nom ?? 'Client supprimé' }}</td>
                        <td>Chambre {{ $reservation->chambre->numero ?? '—' }}</td>
                        <td>{{ $reservation->typeReservation->libelle ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}</td>
                        <td>{{ number_format($reservation->prix_total) }} FCFA</td>
                        <td>
                            <span class="badge bg-{{ $color }}">
                                {{ ucfirst($statut) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.reservations.show', $reservation->id) }}"
                            class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i>Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            Aucune réservation pour le moment
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const moisLabels        = @json($moisLabels);
    const reservationsDatas = @json($reservationsDatas);
    const revenusDatas      = @json($revenusDatas);

    // Réservations par mois
    new Chart(document.getElementById('reservationsChart'), {
        type: 'bar',
        data: {
            labels: moisLabels,
            datasets: [{
                label: 'Réservations',
                data: reservationsDatas,
                backgroundColor: 'rgba(37, 99, 235, 0.7)',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // Revenus par mois
    new Chart(document.getElementById('revenusChart'), {
        type: 'line',
        data: {
            labels: moisLabels,
            datasets: [{
                label: 'Revenus (FCFA)',
                data: revenusDatas,
                borderColor: '#059669',
                backgroundColor: 'rgba(5, 150, 105, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#059669',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

@endsection