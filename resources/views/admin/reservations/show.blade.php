@extends('layouts.admin')

@section('admin-content')
<style>
      @media print {
            .topbar, .sidebar, .admin-footer,
            .d-flex.gap-2, .btn, form { display: none !important; }
            .card { box-shadow: none !important; border: 1px solid #ddd !important; }

            /* ✅ Masquer l'URL et le titre affichés par le navigateur */
            @page {
                margin: 0;
                size: A4;
            }

            body {
                margin: 1.5cm;
            }
        }
</style>
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Détail de la réservation</h2>
        <div class="d-flex gap-2">
            {{-- Imprimer --}}
            <button onclick="window.print()" class="btn btn-outline-secondary">
                <i class="bi bi-printer me-1"></i> Imprimer
            </button>
            {{-- Télécharger PDF --}}
            <a href="{{ route('admin.reservations.recu-pdf', $reservation->id) }}"
               class="btn btn-outline-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i> Télécharger PDF
            </a>
        </div>
    </div>

    <div class="row">

        {{-- IMAGE CHAMBRE --}}
        <div class="col-md-5">
            <div class="card shadow-sm">
                <img src="{{ asset('storage/' . ($reservation->chambre->image ?? 'default.jpg')) }}"
                     class="card-img-top" style="height:300px;object-fit:cover;" alt="Chambre">
                <div class="card-body text-center">
                    <h5>Chambre N° {{ $reservation->chambre->numero ?? 'N/A' }}</h5>
                </div>
            </div>
        </div>

        {{-- INFOS RESERVATION --}}
        <div class="col-md-7">
            <div class="card shadow-sm p-4">

                {{-- Code --}}
                <div class="mb-3">
                    <span class="text-muted small">Code réservation</span>
                    <div>
                        <span class="badge bg-secondary font-monospace fs-6">
                            {{ $reservation->code_reservation ?? '—' }}
                        </span>
                    </div>
                </div>

                <table class="table table-borderless mb-3">
                    <tr>
                        <td class="text-muted">Client</td>
                        <td class="fw-semibold">
                            {{ $reservation->user->nom ?? 'N/A' }}
                            {{ $reservation->user->prenom ?? '' }}
                            <div class="small text-muted">{{ $reservation->user->email ?? '' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Type</td>
                        <td>{{ $reservation->typeReservation->libelle ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Date début</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Date fin</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Durée</td>
                        <td>
                            @if(strtolower($reservation->typeReservation->libelle ?? '') === 'passage')
                                {{ $reservation->nombres_heures }} heure(s)
                                @if($reservation->heure_debut && $reservation->heure_fin)
                                    <span class="text-muted small">
                                        ({{ $reservation->heure_debut }} → {{ $reservation->heure_fin }})
                                    </span>
                                @endif
                            @else
                                {{ $reservation->nombre_jours }} nuit(s)
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Prix total</td>
                        <td class="fw-bold text-success fs-5">
                            {{ number_format($reservation->prix_total) }} FCFA
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Statut</td>
                        <td>
                            <span class="badge bg-{{ match($reservation->statut) {
                                'en_attente' => 'warning',
                                'confirmee'  => 'success',
                                'annulee'    => 'danger',
                                'terminee'   => 'secondary',
                                default      => 'dark'
                            } }}">
                                {{ ucfirst($reservation->statut) }}
                            </span>
                        </td>
                    </tr>

                    {{-- Paiement --}}
                    @php
                        $paiement = $reservation->paiements->where('statut', 'paye')->first();
                    @endphp
                    @if($paiement)
                    <tr>
                        <td class="text-muted">Paiement</td>
                        <td>
                            <span class="badge bg-success">Payé</span>
                            <div class="small text-muted font-monospace">
                                {{ $paiement->reference_transaction }}
                            </div>
                            <div class="small text-muted">
                                {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y à H:i') }}
                                — {{ ucfirst($paiement->mode_paiement) }}
                            </div>
                        </td>
                    </tr>
                    @endif
                </table>

                {{-- ACTIONS --}}
                <div class="d-flex flex-wrap gap-2 mt-2">
                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left me-1"></i> Retour
                    </a>

                    @if($reservation->statut === 'en_attente')
                        <form action="{{ route('admin.reservations.confirmer', $reservation) }}"
                              method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-success"
                                onclick="return confirm('Confirmer cette réservation ?')">
                                <i class="bi bi-check-lg me-1"></i> Confirmer
                            </button>
                        </form>
                    @endif

                    @if(in_array($reservation->statut, ['en_attente','confirmee']))
                        <form action="{{ route('admin.reservations.annuler', $reservation) }}"
                              method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-danger"
                                onclick="return confirm('Annuler cette réservation ?')">
                                <i class="bi bi-x-circle me-1"></i> Annuler
                            </button>
                        </form>
                    @endif

                    @if($reservation->statut === 'confirmee')
                        <form action="{{ route('admin.reservations.terminer', $reservation) }}"
                              method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-secondary"
                                onclick="return confirm('Marquer comme terminée ?')">
                                <i class="bi bi-flag me-1"></i> Terminer
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Style impression --}}
<style>
@media print {
    .topbar, .sidebar, .admin-footer,
    .d-flex.gap-2, .btn, form { display: none !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; }
}
</style>

@endsection