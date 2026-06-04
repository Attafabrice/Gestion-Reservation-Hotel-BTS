@extends('layouts.client')

@section('client-content')

<section class="rooms section">
    <div class="container" data-aos="fade-up">
        <form method="GET" action="{{ route('client.gallery') }}" class="mb-5">
            <div class="row g-3">
                <!-- CAPACITE -->
                <div class="col-md-4">
                    <input type="number"
                        name="capacite"
                        class="form-control"
                        placeholder="Capacité (ex: 2)"
                        value="{{ request('capacite') }}">
                </div>
                <!-- TYPE CHAMBRE -->
                <div class="col-md-4">
                    <select name="type" class="form-control">
                        <option value="">Type de chambre</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}"
                                {{ request('type') == $type->id ? 'selected' : '' }}>
                                {{ $type->libelle }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- BOUTON -->
                <div class="col-md-4">
                    <button class="btn btn-primary w-100">Filtrer</button>
                </div>
            </div>
        </form>

        <!-- LISTE -->
        <div class="row g-4">

            @forelse($chambres as $chambre)
            <div class="col-lg-4 col-md-6" data-aos="zoom-in">

                <div class="room-card">

                    <div class="room-image">
                        <img src="{{ $chambre->image ? asset('storage/'.$chambre->image) : asset('img/default-room.jpg') }}" class="img-fluid">
                    </div>

                    <div class="room-content">

                        <h4>{{ $chambre->type->libelle ?? 'Type inconnu' }}</h4>

                        <p>
                            {{ \Illuminate\Support\Str::limit($chambre->description, 80) }}
                        </p>

                        <div class="room-features">
                            <span><i class="bi bi-people"></i> {{ $chambre->capacite ?? '-' }}</span>
                            <span><i class="bi bi-house"></i> {{ $chambre->surface ?? '-' }} m²</span>
                        </div>

                        <div class="d-flex justify-content-between mt-3">

                            <a href="{{ route('client.room.room-details', $chambre->id) }}"
                               class="btn btn-outline-primary btn-sm">
                                Voir détail
                            </a>

                            <a href="{{ route('client.reservation.create', $chambre->id) }}"
                               class="btn btn-primary btn-sm">
                                Réserver
                            </a>

                        </div>

                    </div>

                </div>

            </div>
            @empty
                <p class="text-center">Aucune chambre disponible</p>
            @endforelse

        </div>

        <div class="mt-5 text-center">
            {{ $chambres->links() }}
        </div>

    </div>
</section>

@endsection