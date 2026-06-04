@extends('layouts.client')

@section('title', 'Reçu de paiement')

@section('client-content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            {{-- Entête reçu --}}
            <div class="card border-0 shadow rounded-3 mb-4" id="recu-paiement">
                {{-- Bandeau haut --}}
                <div class="card-header text-white text-center py-4 rounded-top-3"
                     style="background: #1e2a38">
                    <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                    <h4 class="fw-bold mb-0">Reçu de Paiement</h4>
                    <div class="small mt-1 opacity-75">Grandoria Hôtel</div>
                </div>

                <div class="card-body p-4">

                    {{-- Statut --}}
                    <div class="text-center mb-4">
                        <span class="badge bg-success px-4 py-2 fs-6">
                            <i class="bi bi-check-circle me-1"></i> Paiement confirmé
                        </span>
                    </div>

                    {{-- Référence --}}
                    <div class="bg-light rounded-3 p-3 text-center mb-4">
                        <div class="text-muted small">Référence de paiement</div>
                        <div class="fw-bold font-monospace fs-5">
                            {{ $paiement->reference_transaction }}
                        </div>
                        <div class="text-muted small mt-1">
                            {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y à H:i') }}
                        </div>
                    </div>

                    {{-- Infos client --}}
                    <h6 class="fw-semibold text-muted text-uppercase mb-3"
                        style="font-size:.75rem;letter-spacing:.5px">
                        Informations client
                    </h6>
                    <table class="table table-borderless mb-4">
                        <tr>
                            <td class="text-muted ps-0">Nom</td>
                            <td class="fw-semibold">
                                {{ $paiement->reservation->user->nom ?? '—' }}
                                {{ $paiement->reservation->user->prenom ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Email</td>
                            <td>{{ $paiement->reservation->user->email ?? '—' }}</td>
                        </tr>
                    </table>

                    {{-- Infos réservation --}}
                    <h6 class="fw-semibold text-muted text-uppercase mb-3"
                        style="font-size:.75rem;letter-spacing:.5px">
                        Détail de la réservation
                    </h6>
                    @php
                        $res       = $paiement->reservation;
                        $isPassage = strtolower(trim($res->typeReservation->libelle ?? '')) === 'passage';
                    @endphp
                    <table class="table table-borderless mb-4">
                        <tr>
                            <td class="text-muted ps-0">Code réservation</td>
                            <td>
                                <span class="badge bg-secondary font-monospace">
                                    {{ $res->code_reservation ?? '—' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Chambre</td>
                            <td class="fw-semibold">Chambre {{ $res->chambre->numero ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Type</td>
                            <td>{{ $res->typeReservation->libelle ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Date début</td>
                            <td>{{ \Carbon\Carbon::parse($res->date_debut)->format('d/m/Y') }}</td>
                        </tr>
                        @if($isPassage)
                        <tr>
                            <td class="text-muted ps-0">Créneau</td>
                            <td>{{ $res->heure_debut }} → {{ $res->heure_fin }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Durée</td>
                            <td>{{ $res->nombres_heures }} heure(s)</td>
                        </tr>
                        @else
                        <tr>
                            <td class="text-muted ps-0">Date fin</td>
                            <td>{{ \Carbon\Carbon::parse($res->date_fin)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Durée</td>
                            <td>{{ $res->nombre_jours }} nuit(s)</td>
                        </tr>
                        @endif
                    </table>

                    {{-- Mode de paiement --}}
                    <h6 class="fw-semibold text-muted text-uppercase mb-3"
                        style="font-size:.75rem;letter-spacing:.5px">
                        Paiement
                    </h6>
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted ps-0">Mode</td>
                            <td>
                                @php
                                    $modeLabel = match($paiement->mode_paiement) {
                                        'especes' => '💵 Espèces',
                                        'carte'   => '💳 Carte bancaire',
                                        'mobile'  => '📱 Mobile Money',
                                        default   => $paiement->mode_paiement,
                                    };
                                @endphp
                                {{ $modeLabel }}
                            </td>
                        </tr>
                        <tr class="border-top">
                            <td class="ps-0 fw-bold fs-5">Montant payé</td>
                            <td class="fw-bold fs-5 text-success">
                                {{ number_format($paiement->montant) }} FCFA
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- Pied reçu --}}
                <div class="card-footer text-center text-muted py-3 small">
                    Merci pour votre confiance — Grandoria Hôtel
                </div>
            </div>

            {{-- Actions --}}
           <div class="d-flex gap-2 justify-content-center mb-4">
                {{-- ✅ Télécharger PDF --}}
                <a href="{{ route('client.paiement.recu.pdf', $paiement->id) }}"
                class="btn btn-outline-danger">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Télécharger PDF
                </a>
                {{-- Imprimer --}}
                <button onclick="window.print()" class="btn btn-outline-secondary">
                    <i class="bi bi-printer me-1"></i> Imprimer
                </button>
                <a href="{{ route('client.reservation.index') }}" class="btn btn-primary">
                    <i class="bi bi-calendar-check me-1"></i> Mes réservations
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Style impression --}}
<style>
@media print {
    .navbar, .footer, nav, footer,
    .d-flex.gap-2, .btn { display: none !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; }
    body { background: white !important; }
}
</style>

@endsection