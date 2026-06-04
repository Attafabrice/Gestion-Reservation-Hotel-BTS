@extends('layouts.client')

@section('client-content')

<style>
.detail-link {
    padding: 10px 20px;
    text-decoration: none;
    color: var(--heading-color);
    border: 1px solid var(--accent-color);
    border-radius: 4px;
    transition: all 0.3s ease;
    flex: 1;
    text-align: center;
}

.detail-link:hover {
   background-color: var(--accent-color);
    color: #fff;
}
</style>

<!-- HERO DETAIL -->
<section class="room-detail-hero section">
    <div class="container" data-aos="fade-up">

        <div class="row gy-4 align-items-center">

            <!-- IMAGE -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="main-image">
                    <img src="{{ asset('storage/' . $chambre->image) }}" class="img-fluid rounded">
                </div>
            </div>

            <!-- INFOS -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="hero-content">

                    <h1>{{ $chambre->type->libelle }}</h1>

                    <p class="lead">
                        {{ $chambre->description }}
                    </p>

                    <!-- SPECS -->
                    <div class="hero-features">

                        <div class="feature-item">
                            <i class="bi bi-people"></i>
                            <span>{{ $chambre->capacite ?? '-' }} personnes</span>
                        </div>

                        <div class="feature-item">
                            <i class="bi bi-house"></i>
                            <span>{{ $chambre->surface ?? '-' }} m²</span>
                        </div>

                        <div class="feature-item">
                            <i class="bi bi-layers"></i>
                            <span>Étage {{ $chambre->etage ?? '-' }}</span>
                        </div>

                    </div>

                    <br>

                    <!-- BUTTON -->
                    <div class="hero-buttons">
                        <a href="{{ route('client.reservation.create', $chambre->id) }}" class="detail-link">
                            Réserver
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>


<!-- AMENITIES -->
<section class="room-amenities section light-background">
    <div class="container" data-aos="fade-up">

        <div class="section-title text-center">
            <h2>Équipements</h2>
        </div>

        <div class="row text-center">

            @if($chambre->type && $chambre->type->equipements)
                @foreach($chambre->type->equipements as $equipement)
                <div class="col-md-3 col-6" data-aos="zoom-in">
                    <div class="amenity-box">
                        <i class="bi bi-check-circle"></i>
                        <p>{{ $equipement }}</p>
                    </div>
                </div>
                @endforeach
            @else
                <p>Aucun équipement disponible</p>
            @endif

        </div>
    </div>
</section>


<!-- DESCRIPTION -->
<section class="room-description section">
    <div class="container" data-aos="fade-up">

        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">

                <h2>Description</h2>

                <p>
                    {{ $chambre->description }}
                </p>

            </div>
        </div>

    </div>
</section>


<!-- CTA -->
<section class="room-cta section">
    <div class="container text-center" data-aos="zoom-in">

        <h2>Prêt à réserver cette chambre ?</h2>
        <br>

        @auth
    <a href="{{ route('client.reservation.create', $chambre->id) }}" class="btn btn-primary">
        Réserver
    </a>
    @else
        <a href="{{ route('login') }}" class="btn btn-danger">
            Connectez-vous pour réserver
        </a>
    @endauth

    </div>
</section>

@endsection