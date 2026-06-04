@extends('layouts.admin')
@section('title','Gestion des rôles')

@section('admin-content')
    <div class="d-flex justify-content-between mb-3">
        <h3>Liste des rôles</h3>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            Ajouter un rôle
        </a>
    </div>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $role)
            <tr>
                <td>{{ $role->id }}</td>
                <td>{{ $role->nom }}</td>
                <td>{{ $role->description }}</td>
                <td>
                    @if($role->statut == 'actif')
                        <span class="badge bg-success">Actif</span>
                    @else
                        <span class="badge bg-danger">Inactif</span>
                    @endif
                </td>
                <td class="d-flex gap-2">
                    <a href="{{ route('admin.roles.edit',$role) }}" class="btn btn-warning btn-sm">
                        Modifier
                    </a>
                        {{-- Bouton Activer / Désactiver --}}
                        <form action="{{ route('admin.roles.toggle',$role) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm 
                                {{ $role->statut == 'actif' ? 'btn-danger' : 'btn-success' }}">
                                {{ $role->statut == 'actif' ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.roles.destroy',$role) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-dark btn-sm"
                                onclick="return confirm('Supprimer ce rôle ?')">
                                Supprimer
                            </button>
                        </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
    <div class="mt-3">
        {{ $roles->links() }}
    </div>
@endsection