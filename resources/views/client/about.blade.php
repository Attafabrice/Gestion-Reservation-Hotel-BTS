@extends('layouts.client')

@section('client-content')

<!-- HERO ABOUT -->
<section class="about-hero section">
    <div class="container" data-aos="fade-up">

        <div class="text-center mb-5">
            <h1>À propos de nous</h1>
            <p class="lead">
                Découvrez l’excellence, le confort et le luxe au cœur de notre hôtel.
            </p>
        </div>

    </div>
</section>


<!-- STORY -->
<section class="about-story section">
    <div class="container">

        <div class="row align-items-center gy-4">

            <!-- IMAGE -->
            <div class="col-lg-6" data-aos="fade-right">
                <img src="{{ asset('img/showcase-9.webp') }}" class="img-fluid rounded">
            </div>

            <!-- TEXTE -->
            <div class="col-lg-6" data-aos="fade-left">
                <h2>Notre histoire</h2>

                <p>
                    Depuis plusieurs années, notre hôtel offre une expérience unique
                    alliant confort, luxe et sérénité.
                </p>

                <p>
                    Situé dans un cadre exceptionnel, nous mettons tout en œuvre pour
                    offrir à nos clients un séjour inoubliable.
                </p>

                <p>
                    Chaque chambre est conçue pour garantir bien-être et élégance.
                </p>
            </div>

        </div>

    </div>
</section>


<!-- STATS -->
<section class="about-stats section light-background">
    <div class="container" data-aos="fade-up">

        <div class="row text-center">

            <div class="col-md-3 col-6">
                <h2 class="purecounter"
                    data-purecounter-start="0"
                    data-purecounter-end="150"
                    data-purecounter-duration="1">150</h2>
                <p>Chambres</p>
            </div>

            <div class="col-md-3 col-6">
                <h2 class="purecounter"
                    data-purecounter-start="0"
                    data-purecounter-end="5"
                    data-purecounter-duration="1">5</h2>
                <p>Étoiles</p>
            </div>

            <div class="col-md-3 col-6">
                <h2 class="purecounter"
                    data-purecounter-start="0"
                    data-purecounter-end="24"
                    data-purecounter-duration="1">24</h2>
                <p>Service 24/7</p>
            </div>

            <div class="col-md-3 col-6">
                <h2 class="purecounter"
                    data-purecounter-start="0"
                    data-purecounter-end="98"
                    data-purecounter-duration="1">98</h2>
                <p>Satisfaction %</p>
            </div>

        </div>
    </div>
</section>

    <!-- SERVICES -->
    <section class="about-services section">
        <div class="container" data-aos="fade-up">
            <div class="text-center mb-5">
                <h2>Nos services</h2>
            </div>
            <div class="row text-center gy-4">
                <div class="col-md-3" data-aos="zoom-in">
                    <i class="bi bi-wifi fs-1"></i>
                    <h5>WiFi Gratuit</h5>
                </div>
                <div class="col-md-3" data-aos="zoom-in">
                    <i class="bi bi-car-front fs-1"></i>
                    <h5>Parking sécurisé</h5>
                </div>
                <div class="col-md-3" data-aos="zoom-in">
                    <i class="bi bi-cup-hot fs-1"></i>
                    <h5>Service 24/7</h5>
                </div>
                <div class="col-md-3" data-aos="zoom-in">
                    <i class="bi bi-water fs-1"></i>
                    <h5>Piscine</h5>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="about-cta section">
        <div class="container text-center" data-aos="zoom-in">
            <h2>Prêt à vivre l’expérience ?</h2>
            <p>Réservez votre chambre dès maintenant</p>
                <a href="{{ route('client.gallery') }}" class="btn btn-primary">
                    Voir les chambres
                </a>
        </div>
    </section>

@endsection