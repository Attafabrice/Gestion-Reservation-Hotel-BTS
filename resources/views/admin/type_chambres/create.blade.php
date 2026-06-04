@extends('layouts.admin')

@section('title', 'Créer un Type de Chambre')

@section('admin-content')
<div class="container mt-4">
    <h1 class="mb-4">Ajouter un Type de Chambre</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.type_chambres.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Libellé</label>
            <input type="text" name="libelle" class="form-control" value="{{ old('libelle') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Équipements</label>
            <input type="text" name="equipements" class="form-control" value="{{ old('equipements') }}" placeholder="Ex: TV, Clim, Wi-Fi, Mini bar">
            <small class="text-muted">
                Sépare les équipements par des virgules.
            </small>
        </div>
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('admin.type_chambres.index') }}" class="btn btn-secondary">Annuler</a>

    </form>
</div>
@endsection
