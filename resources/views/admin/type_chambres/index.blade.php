@extends('layouts.admin')

@section('admin-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Liste des types chambres</h2>
    <a href="{{ route('admin.type_chambres.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Ajouter un type de chambre
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Libellé</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($types as $type)
        <tr>
            <td>{{ ucfirst($type->libelle) }}</td>
            <td>{{ $type->description }}</td>
            <td>
                <div class="d-flex gap-1">
                    {{-- ✅ Modifier --}}
                    <a href="{{ route('admin.types_reservation.edit', $type) }}"
                       class="btn btn-sm btn-warning" title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </a>
                    {{-- ✅ Supprimer --}}
                    <form action="{{ route('admin.types_reservation.destroy', $type) }}"
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" title="Supprimer"
                                onclick="return confirm('Supprimer ce type ?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection