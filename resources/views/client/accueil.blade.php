@extends('layouts.client')

@section('client-content')

@php
    $mainRoom = $chambres->first();
@endphp

{{-- ═══════════════════════════════════════ --}}
{{--           SECTION HERO                  --}}
{{-- ═══════════════════════════════════════ --}}
<section id="hotel-hero" class="hotel-hero section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4 align-items-center">

            {{-- TEXTE --}}
            <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
                <div class="hero-content">
                    <h1>Le luxe redéfini à chaque séjour</h1>
                    <p class="lead">
                        Découvrez un confort et une sophistication inégalés dans notre hôtel haut de gamme.
                    </p>
                    <div class="hero-features">
                        <div class="feature-item">
                            <i class="bi bi-wifi"></i>
                            <span>Complimentary WiFi</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-car-front"></i>
                            <span>Valet Parking</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-cup-hot"></i>
                            <span>24/7 Room Service</span>
                        </div>
                    </div>
                    <div class="hero-buttons">
                        <a href="{{ route('client.gallery') }}" class="btn btn-primary">Voir les chambres</a>
                        <a href="{{ route('client.contact') }}" class="btn btn-outline">Nous contacter</a>
                    </div>
                </div>
            </div>

            {{-- IMAGE --}}
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
                <div class="hero-images">
                    <div class="main-image">
                        <img src="{{ asset('img/showcase-3.webp') }}" class="img-fluid" alt="Hotel">
                    </div>
                    <div class="floating-card" data-aos="zoom-in" data-aos-delay="400">
                        <div class="card-content">
                            <div class="rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <h6>Exceptional Experience</h6>
                            <p>"Absolutely stunning hotel! The service was impeccable and the views breathtaking."</p>
                            <div class="guest-info">
                                <img src="{{ asset('img/person-f-3.webp') }}" class="guest-avatar" alt="Guest">
                                <span>Sarah Johnson</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
{{-- ✅ hotel-hero fermé ici --}}

{{-- ═══════════════════════════════════════ --}}
{{--         SECTION ABOUT HOME              --}}
{{-- ═══════════════════════════════════════ --}}
<section id="about-home" class="about-home section">
    <div class="container">
        <div class="row gy-5 align-items-center">

            {{-- Contenu gauche --}}
            <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
                <div class="about-content">
                    <h2>Bienvenue à Grandoria</h2>
                    <p class="lead">Là où le luxe rencontre la tranquillité au cœur du paradis naturel.</p>
                    <p>Niché au milieu de collines vallonnées et de paysages immaculés, Grandview Resort offre une hospitalité exceptionnelle depuis plus de trois décennies. Notre engagement envers l'excellence et notre souci du détail créent une expérience inoubliable pour les voyageurs exigeants.</p>
                    <p>De nos suites élégamment aménagées à nos équipements de classe mondiale, chaque aspect de votre séjour est conçu pour dépasser les attentes.</p>

                    <div class="stats-row">
                        <div class="stat-item">
                            <div class="stat-number">185</div>
                            <div class="stat-label">Luxury Rooms</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">98%</div>
                            <div class="stat-label">Guest Satisfaction</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">30</div>
                            <div class="stat-label">Years of Excellence</div>
                        </div>
                    </div>

                    <div class="about-actions">
                        <a href="{{ route('client.about') }}" class="btn-primary">Notre histoire</a>
                        <a href="{{ route('client.gallery') }}" class="btn-secondary">Voir les chambres</a>
                    </div>
                </div>
            </div>

            {{-- Images droite --}}
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
                <div class="about-images">
                    <div class="main-image">
                        <img src="{{ asset('img/showcase-8.webp') }}" alt="Grandview Resort Main View">
                    </div>
                    <div class="secondary-image">
                        <img src="{{ asset('img/room-12.webp') }}" alt="Luxury Suite Interior">
                    </div>
                    <div class="experience-badge">
                        <div class="badge-content">
                            <span class="badge-number">30+</span>
                            <span class="badge-text">Years<br>Experience</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════ --}}
{{--       SECTION ROOMS SHOWCASE            --}}
{{-- ═══════════════════════════════════════ --}}
<section id="rooms-showcase" class="rooms-showcase section">

    <div class="container section-title" data-aos="fade-up">
        <span class="description-title">Rooms</span>
        <h2>Nos Chambres</h2>
        <p>Découvrez notre sélection de chambres haut de gamme pour un séjour inoubliable</p>
    </div>

    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">

            {{-- CHAMBRE PRINCIPALE --}}
            @if($mainRoom)
            <div class="col-xl-8" data-aos="zoom-in" data-aos-delay="200">
                <div class="hero-room-showcase">
                    <div class="showcase-image-container">
                        <img src="{{ asset('storage/' . $mainRoom->image) }}" class="img-fluid" alt="{{ $mainRoom->type->libelle }}">
                        <div class="room-category-badge">
                            <span>{{ $mainRoom->type->libelle }}</span>
                        </div>
                        <div class="room-details-overlay">
                            <div class="room-specs">
                                <span class="spec-item">
                                    <i class="bi bi-people"></i>
                                    <span>{{ $mainRoom->capacite ?? '-' }} Guests</span>
                                </span>
                                <span class="spec-item">
                                    <i class="bi bi-house"></i>
                                    <span>{{ $mainRoom->surface ?? '-' }}m²</span>
                                </span>
                                <span class="spec-item">
                                    <i class="bi bi-layers"></i>
                                    <span>Étage {{ $mainRoom->etage ?? '-' }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="standard-room-card">
                        <div class="card-content">
                            <h2>{{ $mainRoom->type->libelle }}</h2>
                            <p class="room-description">{{ $mainRoom->description }}</p>
                            <div class="booking-row">
                                <a href="{{ route('client.reservation.create', $mainRoom->id) }}" class="book-link">Réserver</a>
                            </div>
                            <div class="booking-row mt-2">
                                <a href="{{ route('client.room.room-details', $mainRoom->id) }}" class="book-link detail-link">Voir détail</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- AUTRES CHAMBRES --}}
            <div class="col-xl-4">
                <div class="room-list-container">
                    @foreach($chambres->skip(1)->take(3) as $index => $chambre)
                    <div class="standard-room-card" data-aos="slide-left" data-aos-delay="{{ 250 + ($index * 50) }}">
                        <div class="card-image">
                            <img src="{{ asset('storage/' . $chambre->image) }}" class="img-fluid" alt="{{ $chambre->type->libelle }}">
                        </div>
                        <div class="card-content">
                            <h4>{{ $chambre->type->libelle }}</h4>
                            <p>{{ \Illuminate\Support\Str::limit($chambre->description, 100) }}</p>
                            <div class="booking-row">
                                <span class="floor-info">
                                    <i class="bi bi-layers"></i> Étage {{ $chambre->etage }}
                                </span>
                                <a href="{{ route('client.room.room-details', $chambre->id) }}" class="book-link">Voir</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- GRILLE DES CHAMBRES --}}
        <div class="row mt-5 gy-4">
            @foreach($chambres as $index => $chambre)
            <div class="col-lg-3 col-sm-6 col-12"
                data-aos="fade-up"
                data-aos-delay="{{ 100 + ($index * 50) }}">
                <div class="minimal-room-card">
                    <div class="room-image">
                        <img src="{{ asset('storage/' . $chambre->image) }}" class="img-fluid" alt="{{ $chambre->type->libelle }}">
                    </div>
                    <div class="room-summary">
                        <h5>{{ $chambre->type->libelle }}</h5>
                        <small>{{ $chambre->surface }} m²</small>
                        <div class="basic-amenities">
                            @if($chambre->type && $chambre->type->equipements)
                                @foreach($chambre->type->equipements as $equipement)
                                    <i class="bi bi-check"></i>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

@endsection