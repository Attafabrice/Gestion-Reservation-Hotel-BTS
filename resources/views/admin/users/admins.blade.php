@extends('layouts.admin')

@section('title', 'Administrateurs')

@section('admin-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1 fw-bold">Administrateurs</h2>
        <p class="text-muted mb-0">Liste des comptes avec accès administration</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nouvel admin
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Créé le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                    <tr>
                        <td class="text-muted">#{{ $admin->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                     style="width:32px;height:32px;font-size:.8rem;flex-shrink:0">
                                    {{ strtoupper(substr($admin->nom ?? $admin->name, 0, 1)) }}
                                </div>
                                {{ $admin->nom ?? $admin->name }}
                            </div>
                        </td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            <span class="badge bg-primary">
                                {{ $admin->role->libelle ?? '—' }}
                            </span>
                        </td>
                        <td>
                            @if($admin->statut === 'actif')
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-danger">Inactif</span>
                            @endif
                        </td>
                        <td>{{ $admin->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.users.edit', $admin) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                {{-- Ne pas pouvoir supprimer son propre compte --}}
                                @if($admin->id !== auth()->id())
                                <form method="POST"
                                      action="{{ route('admin.users.destroy', $admin) }}"
                                      onsubmit="return confirm('Supprimer cet administrateur ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-person-gear fs-2 d-block mb-2"></i>
                            Aucun administrateur trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($admins->hasPages())
    <div class="mt-3">{{ $admins->links() }}</div>
@endif

@endsection