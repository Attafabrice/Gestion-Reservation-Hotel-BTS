@extends('layouts.admin')

@section('title', 'Éditer un Tarif')

@section('admin-content')
{{-- <h1>Éditer le Tarif</h1> --}}

<form action="{{ route('admin.tarifs.update', $tarif->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="type_chambre">Type de Chambre</label>
        <select name="type_chambre_id" id="type_chambre" class="form-control">
            @foreach($chambres as $chambre)
                <option value="{{ $chambre->id }}" {{ $tarif->type_chambre_id == $chambre->id ? 'selected' : '' }}>
                    {{ $chambre->nom }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="type_reservation">Type de Réservation</label>
        <select name="type_reservation_id" id="type_reservation" class="form-control">
            @foreach($typesReservation as $type)
                <option value="{{ $type->id }}" {{ $tarif->type_reservation_id == $type->id ? 'selected' : '' }}>
                    {{ $type->nom }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="prix">Prix</label>
        <input type="text" name="prix" id="prix" class="form-control" value="{{ $tarif->prix }}">
    </div>

    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>
@endsection