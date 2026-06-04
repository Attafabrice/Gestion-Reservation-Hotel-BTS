@extends('layouts.admin')
@section('title','Créer un rôle')

@section('admin-content')
        <h3 class="mb-4">Créer un rôle</h3>

        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nom du rôle</label>
                <input type="text" name="nom" class="form-control" value="{{ old('nom') }}" required>
                @error('nom')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"  rows="3">{{ old('description') }}</textarea>
            </div>
            <button class="btn btn-primary">
                Créer
            </button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                Retour
            </a>
        </form>
@endsection