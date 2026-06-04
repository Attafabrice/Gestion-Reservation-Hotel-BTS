@extends('layouts.client')

@section('title', 'Paiement')

@section('client-content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <h2 class="fw-bold mb-4">Paiement de la réservation</h2>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Récapitulatif --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Récapitulatif</h5>

                    @php
                        $isPassage = strtolower(trim($reservation->typeReservation->libelle ?? '')) === 'passage';
                    @endphp

                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="text-muted">Chambre</th>
                            <td>Chambre {{ $reservation->chambre->numero }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Type</th>
                            <td>{{ $reservation->typeReservation->libelle ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Date</th>
                            <td>{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</td>
                        </tr>
                        @if($isPassage)
                        <tr>
                            <th class="text-muted">Créneau</th>
                            <td>{{ $reservation->heure_debut }} → {{ $reservation->heure_fin }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Durée</th>
                            <td>{{ $reservation->nombres_heures }} heure(s)</td>
                        </tr>
                        @else
                        <tr>
                            <th class="text-muted">Date fin</th>
                            <td>{{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Durée</th>
                            <td>{{ $reservation->nombre_jours }} nuit(s)</td>
                        </tr>
                        @endif
                        <tr class="border-top">
                            <th>Total à payer</th>
                            <td class="fs-5 fw-bold text-success">
                                {{ number_format($reservation->prix_total) }} FCFA
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Formulaire paiement --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Mode de paiement</h5>

                    <form action="{{ route('client.paiement.store', $reservation->id) }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-4">

                            {{-- Espèces --}}
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="mode_paiement"
                                       id="especes" value="especes" required>
                                <label class="btn btn-outline-secondary w-100 py-3" for="especes">
                                    <i class="bi bi-cash-coin fs-4 d-block mb-1"></i>
                                    Espèces
                                </label>
                            </div>

                            {{-- Carte --}}
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="mode_paiement"
                                       id="carte" value="carte">
                                <label class="btn btn-outline-primary w-100 py-3" for="carte">
                                    <i class="bi bi-credit-card fs-4 d-block mb-1"></i>
                                    Carte bancaire
                                </label>
                            </div>

                            {{-- Mobile --}}
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="mode_paiement"
                                       id="mobile" value="mobile">
                                <label class="btn btn-outline-success w-100 py-3" for="mobile">
                                    <i class="bi bi-phone fs-4 d-block mb-1"></i>
                                    Mobile Money
                                </label>
                            </div>

                        </div>

                        @error('mode_paiement')
                            <div class="text-danger small mb-3">{{ $message }}</div>
                        @enderror

                        <button type="submit" class="btn btn-success w-100 py-3 fw-semibold">
                            <i class="bi bi-lock-fill me-2"></i>
                            Confirmer le paiement de {{ number_format($reservation->prix_total) }} FCFA
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-3 text-center">
                <a href="{{ route('client.reservation.index') }}" class="text-muted small">
                    ← Retour à mes réservations
                </a>
            </div>

        </div>
    </div>
</div>
@endsection