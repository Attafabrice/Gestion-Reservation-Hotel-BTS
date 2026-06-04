@extends('layouts.admin')

@section('title', 'Liste des Tarifs')

@section('admin-content')
{{-- <h1>Liste des Tarifs</h1> --}}

<a href="{{ route('admin.tarifs.create') }}" class="btn btn-primary mb-3">Ajouter un Tarif</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Type Chambre</th>
            <th>Type Réservation</th>
            <th>Prix</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tarifs as $tarif)
            <tr>
                <td>{{ $tarif->typeChambre->libelle }}</td>
                <td>{{ $tarif->typeReservation->libelle }}</td>
                <td>{{ number_format($tarif->prix) }}</td>
                <td>
                    <a href="{{ route('admin.tarifs.edit', $tarif->id) }}" class="btn btn-sm btn-warning">Éditer</a>
                    <form action="{{ route('admin.tarifs.destroy', $tarif->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous supprimer ce tarif ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $tarifs->links() }} <!-- Pagination -->
@endsection