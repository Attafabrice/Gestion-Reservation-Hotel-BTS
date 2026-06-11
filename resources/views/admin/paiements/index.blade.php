@extends('layouts.admin')

@section('title', 'Paiements')

@section('admin-content')

<h2 class="fw-bold mb-1">Paiements</h2>
<p class="text-muted mb-4">Historique de tous les paiements</p>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#ID</th>
                        <th>Client</th>
                        <th>Chambre</th>
                        <th>Montant</th>
                        <th>Mode</th>
                        <th>Statut</th>
                        <th>Référence</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paiements as $paiement)
                    @php
                        $color = match($paiement->statut) {
                            'paye'    => 'success',
                            'partiel' => 'warning',
                            'impaye'  => 'danger',
                            default   => 'secondary'
                        };
                        $modeIcon = match($paiement->mode_paiement) {
                            'carte'   => 'bi-credit-card',
                            'mobile'  => 'bi-phone',
                            default   => 'bi-cash-coin'
                        };
                    @endphp
                    <tr>
                        <td class="text-muted">#{{ $paiement->id }}</td>
                        <td>{{ $paiement->reservation->user->nom ?? '—' }}</td>
                        <td>Chambre {{ $paiement->reservation->chambre->numero ?? '—' }}</td>
                        <td class="fw-semibold">
                            {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                        </td>
                        <td>
                            <i class="bi {{ $modeIcon }} me-1"></i>
                            {{ ucfirst($paiement->mode_paiement) }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $color }}">
                                {{ ucfirst($paiement->statut) }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted font-monospace">
                                {{ $paiement->reference_transaction ?? '—' }}
                            </small>
                        </td>
                        <td>
                            {{ $paiement->date_paiement
                                ? \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i')
                                : '—' }}
                        </td>
                        <td>
                            <a href="{{ route('admin.paiements.show', $paiement->id) }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            Aucun paiement enregistré
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
         <div class="mt-4 ms-3">
            {{ $paiements->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

@endsection