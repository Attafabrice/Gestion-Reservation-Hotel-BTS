<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #333;
            padding: 30px;
        }

        /* ── ENTÊTE ── */
        .header {
            background: #1e2a38;
            color: white;
            text-align: center;
            padding: 24px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 0;
        }
        .header h1 { font-size: 22px; margin-bottom: 4px; }
        .header p  { font-size: 12px; opacity: .8; }

        /* ── BADGE STATUT ── */
        .statut {
            text-align: center;
            background: #f8f9fa;
            padding: 14px;
            border-left: 1px solid #dee2e6;
            border-right: 1px solid #dee2e6;
        }
        .badge-success {
            background: #198754;
            color: white;
            padding: 6px 20px;
            border-radius: 20px;
            font-size: 13px;
        }

        /* ── RÉFÉRENCE ── */
        .reference-box {
            background: #f0f4ff;
            border: 1px solid #d0d9ff;
            border-radius: 6px;
            text-align: center;
            padding: 14px;
            margin: 20px 0;
        }
        .reference-box .label { color: #666; font-size: 11px; }
        .reference-box .value { font-size: 17px; font-weight: bold; color: #1e2a38; }
        .reference-box .date  { color: #888; font-size: 11px; margin-top: 4px; }

        /* ── SECTIONS ── */
        .section-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin: 18px 0 10px;
        }

        /* ── TABLE ── */
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 6px 4px; vertical-align: top; }
        table td:first-child { color: #666; width: 45%; }
        table td:last-child  { font-weight: 500; }

        .total-row td {
            border-top: 2px solid #eee;
            padding-top: 10px;
            font-size: 16px;
            font-weight: bold;
        }
        .total-row td:last-child { color: #198754; }

        /* ── FOOTER ── */
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 14px;
            border-top: 1px solid #eee;
            color: #aaa;
            font-size: 11px;
        }

        /* ── CARD ── */
        .card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }
        .card-body { padding: 20px 24px; }
    </style>
</head>
<body>

<div class="card">

    {{-- Entête --}}
    <div class="header">
        <h1>Reçu de Paiement</h1>
        <p>Grandoria Hôtel</p>
    </div>

    {{-- Statut --}}
    <div class="statut">
        <span class="badge-success">✓ Paiement confirmé</span>
    </div>

    <div class="card-body">

        {{-- Référence --}}
        <div class="reference-box">
            <div class="label">Référence de paiement</div>
            <div class="value">{{ $paiement->reference_transaction }}</div>
            <div class="date">
                {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y à H:i') }}
            </div>
        </div>

        {{-- Infos client --}}
        <div class="section-title">Informations client</div>
        @php $user = $paiement->reservation->user; @endphp
        <table>
            <tr>
                <td>Nom complet</td>
                <td>{{ $user->nom ?? '—' }} {{ $user->prenom ?? '' }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $user->email ?? '—' }}</td>
            </tr>
        </table>

        {{-- Infos réservation --}}
        <div class="section-title">Détail de la réservation</div>
        @php
            $res       = $paiement->reservation;
            $isPassage = strtolower(trim($res->typeReservation->libelle ?? '')) === 'passage';
        @endphp
        <table>
            <tr>
                <td>Code réservation</td>
                <td>{{ $res->code_reservation ?? '—' }}</td>
            </tr>
            <tr>
                <td>Chambre</td>
                <td>Chambre {{ $res->chambre->numero ?? '—' }}</td>
            </tr>
            <tr>
                <td>Type</td>
                <td>{{ $res->typeReservation->libelle ?? '—' }}</td>
            </tr>
            <tr>
                <td>Date début</td>
                <td>{{ \Carbon\Carbon::parse($res->date_debut)->format('d/m/Y') }}</td>
            </tr>
            @if($isPassage)
            <tr>
                <td>Créneau</td>
                <td>{{ $res->heure_debut }} → {{ $res->heure_fin }}</td>
            </tr>
            <tr>
                <td>Durée</td>
                <td>{{ $res->nombres_heures }} heure(s)</td>
            </tr>
            @else
            <tr>
                <td>Date fin</td>
                <td>{{ \Carbon\Carbon::parse($res->date_fin)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Durée</td>
                <td>{{ $res->nombre_jours }} nuit(s)</td>
            </tr>
            @endif
        </table>

        {{-- Paiement --}}
        <div class="section-title">Paiement</div>
        <table>
            <tr>
                <td>Mode de paiement</td>
                <td>
                    @php
                        echo match($paiement->mode_paiement) {
                            'especes' => 'Espèces',
                            'carte'   => 'Carte bancaire',
                            'mobile'  => 'Mobile Money',
                            default   => $paiement->mode_paiement,
                        };
                    @endphp
                </td>
            </tr>
            <tr class="total-row">
                <td>Montant payé</td>
                <td>{{ number_format($paiement->montant) }} FCFA</td>
            </tr>
        </table>

    </div>

    {{-- Footer --}}
    <div class="footer">
        Merci pour votre confiance — Grandoria Hôtel &nbsp;|&nbsp;
        Document généré le {{ now()->format('d/m/Y à H:i') }}
    </div>

</div>

</body>
</html>