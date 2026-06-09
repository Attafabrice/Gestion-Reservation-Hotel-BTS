@extends('layouts.admin')

@section('title', 'Modifier une chambre')
@section('admin-content')

<div class="container mt-4">
    <h1>Modifier la chambre #{{ $chambre->id }}</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.chambres.update', $chambre->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label>Numéro</label>
            <input type="text" name="numero" class="form-control" value="{{ old('numero', $chambre->numero) }}" required>
        </div>

        <div class="mb-3">
            <label>Étage</label>
            <input type="number" name="etage" class="form-control" value="{{ old('etage', $chambre->etage) }}">
        </div>

        <div class="mb-3">
            <label>Capacité</label>
            <input type="number" name="capacite" class="form-control" value="{{ old('capacite', $chambre->capacite) }}">
        </div>

        <div class="mb-3">
            <label>Surface (m²)</label>
            <input type="number" name="surface" class="form-control" value="{{ old('surface', $chambre->surface) }}">
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $chambre->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Image actuelle</label><br>
            @if($chambre->image)
                <img src="{{ asset('storage/' . $chambre->image) }}" alt="Image chambre" width="150">
            @else
                -
            @endif
        </div>

        <div class="mb-3">
            <label>Changer l'image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="mb-3">
            <label>Type de chambre</label>
            <select name="type_chambre_id" class="form-control" required>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ old('type_chambre_id', $chambre->type_chambre_id) == $type->id ? 'selected' : '' }}>
                        {{ $type->libelle }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Statut</label>
            <select name="statut" class="form-control" required>
                <option value="libre" {{ old('statut', $chambre->statut) == 'libre' ? 'selected' : '' }}>Libre</option>
                <option value="occupee" {{ old('statut', $chambre->statut) == 'occupee' ? 'selected' : '' }}>Occupée</option>
                <option value="maintenance" {{ old('statut', $chambre->statut) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Mettre à jour</button>
        <a href="{{ route('admin.chambres.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>

@endsection