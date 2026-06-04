@extends('layouts.admin')

@section('admin-content')

<div class="container">
    <h2>Modifier Type de Réservation</h2>

    <form action="{{ route('admin.types_reservation.update', $typeReservation) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Libellé</label>
            <select name="libelle" class="form-control">
                <option value="passage" {{ $typeReservation->libelle == 'passage' ? 'selected' : '' }}>Passage</option>
                <option value="nuitée" {{ $typeReservation->libelle == 'nuitée' ? 'selected' : '' }}>Nuitée</option>
                <option value="sejour" {{ $typeReservation->libelle == 'sejour' ? 'selected' : '' }}>Séjour</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Prix (FCFA)</label>
            <input type="number" name="prix" class="form-control"
                   value="{{ $typeReservation->prix }}" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">
                {{ $typeReservation->description }}
            </textarea>
        </div>

        <button class="btn btn-primary">Mettre à jour</button>
    </form>
</div>

@endsection