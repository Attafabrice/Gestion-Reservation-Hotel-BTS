@extends('layouts.admin')

@section('title', 'Ajouter une chambre')
@section('admin-content')

<div class="container mt-4">
    <h1>Ajouter une chambre</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.chambres.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Numéro</label>
            <input type="text" name="numero" class="form-control" value="{{ old('numero') }}" required>
        </div>

        <div class="mb-3">
            <label>Étage</label>
            <input type="number" name="etage" class="form-control" value="{{ old('etage') }}">
        </div>

        <div class="mb-3">
            <label>Capacité</label>
            <input type="number" name="capacite" class="form-control" value="{{ old('capacite') }}">
        </div>

        <div class="mb-3">
            <label>Surface (m²)</label>
            <input type="number" name="surface" class="form-control" value="{{ old('surface') }}">
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Type de chambre</label>
            <select name="type_chambre_id" class="form-control" required>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ old('type_chambre_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->libelle }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Statut</label>
            <select name="statut" class="form-control" required>
                <option value="libre" {{ old('statut') == 'libre' ? 'selected' : '' }}>Libre</option>
                <option value="occupee" {{ old('statut') == 'occupee' ? 'selected' : '' }}>Occupée</option>
                <option value="maintenance" {{ old('statut') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="{{ route('admin.chambres.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>

@endsection