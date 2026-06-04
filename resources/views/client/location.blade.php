@extends('layouts.client')

@section('client-content')
<style>
    .map-container iframe {
    border-radius: 10px;
    }
</style>

<section class="location section">
    <div class="container" data-aos="fade-up">

        <!-- TITRE -->
        <div class="section-title text-center mb-5">
            <h2>Localisation de l'hôtel</h2>
            <p>Retrouvez-nous facilement et planifiez votre visite</p>
        </div>

        <div class="row g-5">

            <!-- CARTE -->
            <div class="col-lg-8" data-aos="zoom-in">
                <div class="map-container">
                   <iframe 
                        src="https://maps.google.com/maps?q=Cocody%2C%20Abidjan&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%" 
                        height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>

            <!-- INFOS -->
            <div class="col-lg-4" data-aos="fade-left">

                <div class="card p-4 shadow-sm">

                    <h4>Grandoria Hotel</h4>

                    <p>
                        📍 Cocody, Abidjan, Côte d'Ivoire  
                        📞 +225 07 00 00 00 00  
                        ✉️ contact@grandoria.com
                    </p>

                    <hr>

                    <h5>Heures d'ouverture</h5>
                    <p>24h / 24 - 7j / 7</p>

                    <hr>

                    <!-- BOUTON GOOGLE MAP -->
                    <a href="https://www.google.com/maps?q=Abidjan"
                       target="_blank"
                       class="btn btn-primary w-100">
                        Voir sur Google Maps
                    </a>

                </div>

            </div>

        </div>

    </div>
</section>

@endsection