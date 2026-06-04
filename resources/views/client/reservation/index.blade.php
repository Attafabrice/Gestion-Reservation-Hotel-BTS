@extends('layouts.client')

@section('title', 'Mes réservations')

@section('client-content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Mes réservations</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Chambre</th>
                    <th>Type</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Durée</th>
                    <th>Prix total</th>
                    <th>Statut</th>
                    <th>Paiement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
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
                    // Vérification paiement
                    $dejaPaye = $reservation->paiements->where('statut', 'paye')->count() > 0;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <span class="badge bg-secondary font-monospace">
                            {{ $reservation->code_reservation }}
                        </span>
                    </td>
                    <td>Chambre {{ $reservation->chambre->numero }}</td>
                    <td>{{ $reservation->typeReservation->libelle }}</td>
                    <td>{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</td>
                    <td>
                        @if($isPassage)
                            {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}
                        @else
                            {{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}
                        @endif
                    </td>
                    <td>
                        @if($isPassage)
                            <span class="badge bg-info">
                                {{ $reservation->nombres_heures ?? 0 }} heure(s)
                                @if($reservation->heure_debut && $reservation->heure_fin)
                                    <br><small>{{ $reservation->heure_debut }} → {{ $reservation->heure_fin }}</small>
                                @endif
                            </span>
                        @else
                            <span class="badge bg-primary">
                                {{ $reservation->nombre_jours ?? 0 }} nuit(s)
                            </span>
                        @endif
                    </td>
                    <td>{{ number_format($reservation->prix_total) }} FCFA</td>
                    <td>
                        <span class="badge bg-{{ $color }}">{{ ucfirst($statut) }}</span>
                    </td>

                    {{--  Colonne paiement --}}
                    <td>
                        @if($statut === 'confirmee')
                            @if($dejaPaye)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Payé
                                </span>
                            @else
                                <span class="badge bg-danger">Non payé</span>
                            @endif
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="d-flex flex-wrap gap-1">
                        <a href="{{ route('client.reservation.show', $reservation->id) }}"
                           class="btn btn-info btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>

                        {{-- Annuler si en attente --}}
                        @if($statut === 'en_attente')
                            <form action="{{ route('client.reservation.annuler', $reservation->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Annuler cette réservation ?')">
                                    <i class="bi bi-x-circle"></i> Annuler
                                </button>
                            </form>
                        @endif

                        {{--  Payer si confirmée et non payée --}}
                        @if($statut === 'confirmee' && !$dejaPaye)
                            <a href="{{ route('client.paiement.show', $reservation->id) }}"
                               class="btn btn-success btn-sm">
                                <i class="bi bi-credit-card me-1"></i>Payer
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            Aucune réservation trouvée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $reservations->links() }}
</div>
@endsection