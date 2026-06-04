@extends('layouts.admin')

@section('title', 'Liste des clients')

@section('admin-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Liste des clients</h2>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nouvel utilisateur
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nom complet</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Rôle</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->nom }} {{ $user->prenoms }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->telephone }}</td>
            <td>{{ $user->role->nom }}</td>
            <td>
                <span class="badge {{ $user->statut === 'actif' ? 'bg-success' : 'bg-secondary' }}">
                    {{ ucfirst($user->statut) }}
                </span>
            </td>
            <td>
                <div class="d-flex gap-1">
                    {{-- ✅ Éditer --}}
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="btn btn-sm btn-warning" title="Éditer">
                        <i class="bi bi-pencil"></i>
                    </a>

                    {{-- ✅ Activer / Désactiver --}}
                    <form action="{{ route('admin.users.toggle-status', $user) }}"
                          method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="btn btn-sm {{ $user->statut === 'actif' ? 'btn-secondary' : 'btn-success' }}"
                                title="{{ $user->statut === 'actif' ? 'Désactiver' : 'Activer' }}">
                            <i class="bi bi-{{ $user->statut === 'actif' ? 'toggle-on' : 'toggle-off' }}"></i>
                        </button>
                    </form>

                    {{-- ✅ Supprimer --}}
                    <form action="{{ route('admin.users.destroy', $user) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Supprimer cet utilisateur ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4 ms-3">
    {{ $users->links('pagination::bootstrap-5') }}
</div>

@endsection