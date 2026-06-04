@extends('layouts.admin')

@section('admin-content')
<style>
        .card {
    border-radius: 10px;
}

.badge {
    font-size: 0.8rem;
    padding: 6px 10px;
}
</style>
<div class="container mt-4">
        <h2>
            Messages reçus
            <span class="badge bg-success">{{ $nonLus }} nouveaux</span>
        </h2>

    @foreach($contacts as $contact)
    <div class="card mb-3 p-3 d-flex justify-content-between align-items-center flex-row">
        <div>
            <h5>{{ $contact->nom }} ({{ $contact->email }})</h5>
            <p>{{ \Illuminate\Support\Str::limit($contact->message, 80) }}</p>
        </div>

        <!-- BADGE -->
        <div>
            @if(!$contact->lu)
                <span class="badge bg-success">Non lu</span>
            @else
                <span class="badge bg-primary">Lu</span>
            @endif
        </div>

        <!-- ACTIONS -->
        <div class="d-flex gap-2">
            <a href="{{ route('admin.contacts.show', $contact->id) }}"
            class="btn btn-sm btn-outline-primary">
                Voir
            </a>
            <form method="POST" action="{{ route('admin.contacts.destroy', $contact->id) }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
@endforeach

    {{ $contacts->links() }}

</div>

@endsection