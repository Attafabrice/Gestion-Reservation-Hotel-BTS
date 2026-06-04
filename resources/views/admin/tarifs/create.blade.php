@extends('layouts.admin')

@section('title', 'Ajouter un Tarif')

@section('admin-content')
<h1>Ajouter un Tarif</h1>

<form action="{{ route('admin.tarifs.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="type_chambre">Type de Chambre</label>
        <select name="type_chambre_id" id="type_chambre" class="form-control">
            @foreach($chambres as $chambre)
                <option value="{{ $chambre->id }}">{{ $chambre->libelle }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="type_reservation">Type de Réservation</label>
        <select name="type_reservation_id" id="type_reservation" class="form-control">
            @foreach($typesReservation as $type)
                <option value="{{ $type->id }}">{{ $type->libelle }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="prix">Prix</label>
        <input type="text" name="prix" id="prix" class="form-control" value="{{ old('prix') }}">
    </div>

    <button type="submit" class="btn btn-success">Enregistrer</button>
</form>
@endsection