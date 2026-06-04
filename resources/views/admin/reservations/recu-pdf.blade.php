<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size:13px; color:#333; padding:30px; }

        .header { background:#1e2a38; color:white; text-align:center; padding:24px; border-radius:8px 8px 0 0; }
        .header h1 { font-size:20px; margin-bottom:4px; }
        .header p  { font-size:11px; opacity:.8; }

        .statut { text-align:center; background:#f8f9fa; padding:12px;
                  border-left:1px solid #dee2e6; border-right:1px solid #dee2e6; }
        .badge-success  { background:#198754; color:white; padding:5px 16px; border-radius:20px; font-size:12px; }
        .badge-warning  { background:#ffc107; color:#333; padding:5px 16px; border-radius:20px; font-size:12px; }
        .badge-danger   { background:#dc3545; color:white; padding:5px 16px; border-radius:20px; font-size:12px; }
        .badge-secondary{ background:#6c757d; color:white; padding:5px 16px; border-radius:20px; font-size:12px; }

        .code-box { background:#f0f4ff; border:1px solid #d0d9ff; border-radius:6px;
                    text-align:center; padding:12px; margin:18px 0; }
        .code-box .label { color:#666; font-size:10px; }
        .code-box .value { font-size:16px; font-weight:bold; color:#1e2a38; }

        .section-title { font-size:10px; text-transform:uppercase; letter-spacing:1px;
                         color:#888; border-bottom:1px solid #eee; padding-bottom:4px; margin:16px 0 8px; }

        table { width:100%; border-collapse:collapse; }
        table td { padding:6px 4px; vertical-align:top; }
        table td:first-child { color:#666; width:45%; }
        table td:last-child  { font-weight:500; }

        .total-row td { border-top:2px solid #eee; padding-top:10px; font-size:15px; font-weight:bold; }
        .total-row td:last-child { color:#198754; }

        .card { border:1px solid #dee2e6; border-radius:8px; overflow:hidden; }
        .card-body { padding:20px 24px; }

        .footer { text-align:center; margin-top:24px; padding-top:12px;
                  border-top:1px solid #eee; color:#aaa; font-size:11px; }
      
    </style>
</head>
<body>
<div class="card">

    <div class="header">
        <h1>Fiche de Réservation</h1>
        <p>Grandoria Hôtel</p>
    </div>

    <div class="statut">
        @php
            $badge = match($reservation->statut) {
                'confirmee'  => 'badge-success',
                'en_attente' => 'badge-warning',
                'annulee'    => 'badge-danger',
                default      => 'badge-secondary',
            };
        @endphp
        <span class="{{ $badge }}">{{ ucfirst($reservation->statut) }}</span>
    </div>

    <div class="card-body">

        {{-- Code --}}
        <div class="code-box">
            <div class="label">Code réservation</div>
            <div class="value">{{ $reservation->code_reservation ?? '—' }}</div>
        </div>

        {{-- Client --}}
        <div class="section-title">Informations client</div>
        <table>
            <tr>
                <td>Nom complet</td>
                <td>{{ $reservation->user->nom ?? '—' }} {{ $reservation->user->prenom ?? '' }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $reservation->user->email ?? '—' }}</td>
            </tr>
        </table>

        {{-- Réservation --}}
        <div class="section-title">Détail de la réservation</div>
        @php $isPassage = strtolower(trim($reservation->typeReservation->libelle ?? '')) === 'passage'; @endphp
        <table>
            <tr>
                <td>Chambre</td>
                <td>Chambre {{ $reservation->chambre->numero ?? '—' }}</td>
            </tr>
            <tr>
                <td>Type</td>
                <td>{{ $reservation->typeReservation->libelle ?? '—' }}</td>
            </tr>
            <tr>
                <td>Date début</td>
                <td>{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</td>
            </tr>
            @if($isPassage)
            <tr>
                <td>Créneau</td>
                <td>{{ $reservation->heure_debut }} → {{ $reservation->heure_fin }}</td>
            </tr>
            <tr>
                <td>Durée</td>
                <td>{{ $reservation->nombres_heures }} heure(s)</td>
            </tr>
            @else
            <tr>
                <td>Date fin</td>
                <td>{{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Durée</td>
                <td>{{ $reservation->nombre_jours }} nuit(s)</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>Prix total</td>
                <td>{{ number_format($reservation->prix_total) }} FCFA</td>
            </tr>
        </table>

        {{-- Paiement si existant --}}
        @php $paiement = $reservation->paiements->where('statut', 'paye')->first(); @endphp
        @if($paiement)
        <div class="section-title">Paiement</div>
        <table>
            <tr>
                <td>Référence</td>
                <td>{{ $paiement->reference_transaction }}</td>
            </tr>
            <tr>
                <td>Mode</td>
                <td>{{ ucfirst($paiement->mode_paiement) }}</td>
            </tr>
            <tr>
                <td>Date paiement</td>
                <td>{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y à H:i') }}</td>
            </tr>
            <tr>
                <td>Montant payé</td>
                <td style="color:#198754;font-weight:bold">{{ number_format($paiement->montant) }} FCFA</td>
            </tr>
        </table>
        @endif

    </div>

    <div class="footer">
        Grandoria Hôtel &nbsp;|&nbsp; Document généré le {{ now()->format('d/m/Y à H:i') }}
    </div>

</div>
</body>
</html>