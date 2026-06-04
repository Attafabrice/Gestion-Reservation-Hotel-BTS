@extends('layouts.admin')

@section('title', 'Détail paiement')

@section('admin-content')

<h2 class="fw-bold mb-1">Détail du paiement #{{ $paiement->id }}</h2>
<p class="text-muted mb-4">
    Référence : <span class="font-monospace">{{ $paiement->reference_transaction ?? '—' }}</span>
</p>

@php
    $color = match($paiement->statut) {
        'paye'    => 'success',
        'partiel' => 'warning',
        'impaye'  => 'danger',
        default   => 'secondary'
    };
@endphp

<div class="row g-3">

    {{-- Infos paiement --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Informations paiement</h5>
                <table class="table table-borderless mb-0">
                    <tr>
                        <th class="text-muted">Statut</th>
                        <td>
                            <span class="badge bg-{{ $color }}">
                                {{ ucfirst($paiement->statut) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Montant</th>
                        <td class="fw-bold fs-5">
                            {{ number_format($paiement->montant) }} FCFA
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Mode</th>
                        <td>{{ ucfirst($paiement->mode_paiement) }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Date</th>
                        <td>
                            {{ $paiement->date_paiement
                                ? \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i')
                                : '—' }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Infos réservation --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Réservation liée</h5>
                <table class="table table-borderless mb-0">
                    <tr>
                        <th class="text-muted">Client</th>
                        <td>{{ $paiement->reservation->user->nom ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Chambre</th>
                        {{-- numero au lieu de nom --}}
                        <td>Chambre {{ $paiement->reservation->chambre->numero ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Type</th>
                        <td>{{ $paiement->reservation->typeReservation->libelle ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Période</th>
                        <td>
                            {{ \Carbon\Carbon::parse($paiement->reservation->date_debut)->format('d/m/Y') }}
                            →
                            {{ \Carbon\Carbon::parse($paiement->reservation->date_fin)->format('d/m/Y') }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Prix total</th>
                        <td class="fw-bold">
                            {{ number_format($paiement->reservation->prix_total) }} FCFA
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="mt-4">
    <a href="{{ route('admin.paiements.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour
    </a>
</div>

@endsection