@extends('layouts.admin')

@section('title', 'Éditer utilisateur')

@section('admin-content')
<div class="container mt-4">
    <h2 class="mb-4">Éditer un client</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom', $user->nom) }}" required>
        </div>

        <div class="mb-3">
            <label for="prenoms" class="form-label">Prénoms</label>
            <input type="text" class="form-control" id="prenoms" name="prenoms" value="{{ old('prenoms', $user->prenoms) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" class="form-control" id="telephone" name="telephone" value="{{ old('telephone', $user->telephone) }}" required>
        </div>

        <div class="mb-3">
            <label for="role_id" class="form-label">Rôle</label>
            <select class="form-select" id="role_id" name="role_id" required>
                <option value="">Sélectionner un rôle</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{(old('role_id', $user->role_id) == $role->id) ? 'selected' : '' }}>{{ $role->nom }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection