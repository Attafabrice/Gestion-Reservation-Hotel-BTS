@extends('layouts.admin')

@section('admin-content')

<div class="container">
    <h2>Créer un Type de Réservation</h2>

    <form action="{{ route('admin.types_reservation.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Libellé</label>
            <select name="libelle" class="form-control">
                <option value="passage">Passage</option>
                <option value="nuitée">Nuitée</option>
                <option value="sejour">Séjour</option>
            </select>
        </div>

        {{-- <div class="mb-3">
            <label>Prix (FCFA)</label>
            <input type="number" name="prix" class="form-control" required>
        </div> --}}

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <button class="btn btn-success">Enregistrer</button>
    </form>
</div>

@endsection