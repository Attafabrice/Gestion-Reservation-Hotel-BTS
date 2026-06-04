@extends('layouts.admin')

@section('title', 'Éditer Type de Chambre')

@section('admin-content')
<div class="container mt-4">
    <h1 class="mb-4">Éditer Type de Chambre</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.type_chambres.update', $typeChambre->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="libelle" class="form-label">Libellé</label>
            <input type="text" name="libelle" id="libelle" class="form-control @error('libelle') is-invalid @enderror" value="{{ old('libelle', $typeChambre->libelle) }}" required>
            @error('libelle')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $typeChambre->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="equipements" class="form-label">Équipements (séparés par une virgule)</label>
            <input type="text" name="equipements" id="equipements" class="form-control @error('equipements') is-invalid @enderror"
                value="{{ old('equipements', implode(',', $typeChambre->equipements ?? [])) }}">
            @error('equipements')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Mettre à jour</button>
        <a href="{{ route('admin.type_chambres.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
