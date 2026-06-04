@extends('layouts.admin')

@section('title', 'Liste des chambres')

@section('admin-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Liste des chambres</h2>
    <a href="{{ route('admin.chambres.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Ajouter une chambre
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Numéro</th>
                <th>Étage</th>
                <th>Capacité</th>
                <th>Surface</th>
                <th>Type</th>
                <th>Description</th>
                <th>Image</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($chambres as $chambre)
            <tr>
                <td>{{ $chambre->id }}</td>
                <td>{{ $chambre->numero }}</td>
                <td>{{ $chambre->etage ?? '—' }}</td>
                <td>{{ $chambre->capacite ?? '—' }}</td>
                <td>{{ $chambre->surface ?? '—' }}</td>
                <td>{{ $chambre->type->libelle ?? '—' }}</td>
                <td>{{ Str::limit($chambre->description ?? '—', 50) }}</td>
                <td>
                    @if($chambre->image)
                        <img src="{{ asset('storage/' . $chambre->image) }}"
                             alt="Chambre" width="60" class="rounded">
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <span class="badge
                        @if($chambre->statut == 'libre') bg-success
                        @elseif($chambre->statut == 'occupee') bg-warning
                        @else bg-danger @endif">
                        {{ ucfirst($chambre->statut) }}
                    </span>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        {{-- ✅ Éditer --}}
                        <a href="{{ route('admin.chambres.edit', $chambre->id) }}"
                           class="btn btn-sm btn-warning" title="Éditer">
                            <i class="bi bi-pencil"></i>
                        </a>
                        {{-- ✅ Supprimer --}}
                        <form action="{{ route('admin.chambres.destroy', $chambre->id) }}"
                              method="POST"
                              onsubmit="return confirm('Supprimer cette chambre ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center text-muted py-4">
                    Aucune chambre trouvée.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $chambres->links('pagination::bootstrap-5') }}
</div>

@endsection