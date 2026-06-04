@extends('layouts.admin')
@section('title','Modifier rôle')

@section('admin-content')
        <h3 class="mb-4">Modifier le rôle</h3>
        <form action="{{ route('admin.roles.update',$role) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nom du rôle</label>
                <input type="text" name="nom" class="form-control" value="{{ old('nom',$role->nom) }}" required>
                @error('nom')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3"> 
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description',$role->description) }}</textarea>
            </div>
            <button class="btn btn-success">
                Mettre à jour
            </button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                Retour
            </a>
        </form>
@endsection