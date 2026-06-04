@extends('layouts.client')

@section('title', 'Détail réservation')

@section('client-content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Détail de la réservation #{{ $reservation->id }}</h2>

    @php
        $isPassage = strtolower(trim($reservation->typeReservation->libelle ?? '')) === 'passage';
        $statut    = $reservation->statut;
        $color     = match($statut) {
            'en_attente' => 'warning',
            'confirmee'  => 'success',
            'annulee'    => 'danger',
            'terminee'   => 'secondary',
            default      => 'dark'
        };
        //  Vérification paiement
        $dejaPaye = $reservation->paiements->where('statut', 'paye')->count() > 0;
    @endphp

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <h5 class="fw-semibold mb-3">Informations générales</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted">Statut</th>
                            <td><span class="badge bg-{{ $color }}">{{ ucfirst($statut) }}</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Chambre</th>
                            <td>Chambre {{ $reservation->chambre->numero }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Type de chambre</th>
                            <td>{{ $reservation->chambre->type->libelle ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Type de réservation</th>
                            <td>{{ $reservation->typeReservation->libelle }}</td>
                        </tr>

                        {{-- Statut paiement --}}
                        @if($statut === 'confirmee')
                        <tr>
                            <th class="text-muted">Paiement</th>
                            <td>
                                @if($dejaPaye)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Payé
                                    </span>
                                @else
                                    <span class="badge bg-danger">Non payé</span>
                                @endif
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="fw-semibold mb-3">Période et tarifs</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted">Date</th>
                            <td>{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</td>
                        </tr>

                        @if($isPassage)
                        <tr>
                            <th class="text-muted">Heure d'arrivée</th>
                            <td>{{ $reservation->heure_debut ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Heure de départ</th>
                            <td>{{ $reservation->heure_fin ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Durée</th>
                            <td>{{ $reservation->nombres_heures ?? 0 }} heure(s)</td>
                        </tr>
                        @else
                        <tr>
                            <th class="text-muted">Date fin</th>
                            <td>{{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Durée</th>
                            <td>{{ $reservation->nombre_jours ?? 0 }} nuit(s)</td>
                        </tr>
                        @endif

                        <tr class="border-top">
                            <th>Prix total</th>
                            <td class="fw-bold fs-5 text-success">
                                {{ number_format($reservation->prix_total) }} FCFA
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{--  Boutons d'action --}}
    <div class="mt-4 d-flex gap-2 flex-wrap">

        <a href="{{ route('client.reservation.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>

        {{-- Annuler si en attente --}}
        @if($statut === 'en_attente')
            <form action="{{ route('client.reservation.annuler', $reservation->id) }}"
                  method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Annuler cette réservation ?')">
                    <i class="bi bi-x-circle me-1"></i>Annuler la réservation
                </button>
            </form>
        @endif

        {{--  Payer si confirmée et non payée --}}
        @if($statut === 'confirmee' && !$dejaPaye)
            <a href="{{ route('client.paiement.show', $reservation->id) }}"
               class="btn btn-success">
                <i class="bi bi-credit-card me-1"></i>
                Payer {{ number_format($reservation->prix_total) }} FCFA
            </a>
        @endif

    </div>
</div>
@endsection