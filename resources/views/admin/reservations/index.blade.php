@extends('layouts.admin')

@section('admin-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Liste des réservations</h2>
    {{-- ✅ Créer — icône + texte --}}
    <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nouvelle réservation
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form method="GET" action="{{ route('admin.reservations.index') }}" class="mb-4">
    <div class="row g-2 align-items-center">
        <div class="col-md-3">
            <input type="text" name="search_code" class="form-control"
                placeholder="Code réservation (ex: RES-2026-...)"
                value="{{ request('search_code') }}">
        </div>
        <div class="col-md-2">
            <input type="text" name="search_nom" class="form-control"
                placeholder="Nom du client" value="{{ request('search_nom') }}">
        </div>
        <div class="col-md-2">
            <input type="text" name="search_prenom" class="form-control"
                placeholder="Prénom du client" value="{{ request('search_prenom') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="search_date_debut" class="form-control"
                title="Date début" value="{{ request('search_date_debut') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="search_date_fin" class="form-control"
                title="Date fin" value="{{ request('search_date_fin') }}">
        </div>
        <div class="col-md-1">
            <button class="btn btn-primary w-100" type="submit">Filtrer</button>
        </div>
        @if(request('search_code') || request('search_nom') || request('search_prenom') || request('search_date_debut') || request('search_date_fin'))
        <div class="col-auto">
            <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline-danger">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>
        @endif
    </div>
</form>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Code</th>
            <th>Client</th>
            <th>Chambre</th>
            <th>Type</th>
            <th>Date début</th>
            <th>Date fin</th>
            <th>Durée</th>
            <th>Prix</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reservations as $reservation)
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
        @endphp
        <tr>
            <td>
                <span class="badge bg-secondary font-monospace">
                    {{ $reservation->code_reservation ?? '—' }}
                </span>
            </td>
            <td>{{ $reservation->user->nom ?? 'Client supprimé' }}</td>
            <td>Chambre {{ $reservation->chambre->numero ?? '—' }}</td>
            <td>{{ $reservation->typeReservation->libelle ?? '—' }}</td>
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
                    <span class="badge bg-info">{{ $reservation->nombres_heures ?? 0 }} heure(s)</span>
                @else
                    <span class="badge bg-primary">{{ $reservation->nombre_jours ?? 0 }} nuit(s)</span>
                @endif
            </td>
            <td>{{ number_format($reservation->prix_total ?? 0) }} FCFA</td>
            <td>
                <span class="badge bg-{{ $color }}">{{ ucfirst($statut) }}</span>
            </td>
            <td>
                <div class="d-flex flex-wrap gap-1">

                    {{-- ✅ Voir — icône œil --}}
                    <a href="{{ route('admin.reservations.show', $reservation->id) }}"
                       class="btn btn-info btn-sm" title="Voir">
                        <i class="bi bi-eye"></i>
                    </a>

                    {{-- ✅ Modifier — icône crayon --}}
                    @if(!in_array($statut, ['annulee', 'terminee']))
                        <a href="{{ route('admin.reservations.edit', $reservation->id) }}"
                           class="btn btn-primary btn-sm" title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </a>
                    @endif

                    {{-- Confirmer — texte gardé car action métier importante --}}
                    @if($statut === 'en_attente')
                        <form action="{{ route('admin.reservations.confirmer', $reservation->id) }}"
                              method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-success btn-sm" type="submit" title="Confirmer"
                                onclick="return confirm('Confirmer cette réservation ?')">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </form>
                    @endif

                    {{-- Annuler --}}
                    @if(in_array($statut, ['en_attente', 'confirmee']))
                        <form action="{{ route('admin.reservations.annuler', $reservation->id) }}"
                              method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-warning btn-sm" type="submit" title="Annuler"
                                onclick="return confirm('Annuler cette réservation ?')">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </form>
                    @endif

                    {{-- Terminer --}}
                    @if($statut === 'confirmee')
                        <form action="{{ route('admin.reservations.terminer', $reservation->id) }}"
                              method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-secondary btn-sm" type="submit" title="Terminer"
                                onclick="return confirm('Marquer comme terminée ?')">
                                <i class="bi bi-flag"></i>
                            </button>
                        </form>
                    @endif

                    {{--  Supprimer — icône poubelle --}}
                    @if($statut !== 'confirmee')
                        <form action="{{ route('admin.reservations.destroy', $reservation) }}"
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" title="Supprimer"
                                onclick="return confirm('Supprimer cette réservation ?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @endif

                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4 ms-3">
    {{ $reservations->links('pagination::bootstrap-5') }}
</div>

@endsection