@extends('layouts.client')

@section('client-content')
    <style>
        .contact .info-box {
            border-radius: 10px;
            background: #fff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .contact .info-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .contact .form-control {
            border-radius: 6px;
        }

        .contact .btn {
            padding: 12px;
            font-weight: 600;
        }
        
        .opening-hours-box {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        }
        
        .opening-hours-box .hours-item {
            border-bottom: 1px solid #eee;
            padding: 12px 0;
        }
        
        .opening-hours-box .hours-item:last-child {
            border-bottom: none;
        }
        .contact-card {
            border-radius: 12px;
            border: none;
            transition: all 0.3s ease;
        }
        
        .contact-card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
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

<section class="contact section py-5">
    <div class="container" data-aos="fade-up">
        <!-- TITRE -->
        <div class="section-title text-center mb-5">
            <h2 class="fw-bold">Envoyez-nous un message</h2>
            <p class="text-muted">Nous sommes disponibles pour vous aider</p>
        </div>
        
        <div class="row g-4">
            <!-- FORMULAIRE (colonne de gauche) -->
            <div class="col-lg-7" data-aos="fade-right">
                <div class="card contact-card p-4 shadow-sm">
                    <form method="POST" action="{{ route('client.contact.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="nom" class="form-control" placeholder="Nom complet" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                            </div>
                            <div class="col-12">
                                <input type="text" name="sujet" class="form-control" placeholder="Sujet">
                            </div>
                            <div class="col-12">
                                <textarea name="message" rows="5" class="form-control" placeholder="Votre message" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="detail-link">
                                    Envoyer le message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- COORDONNÉES (colonne de droite) -->
            <div class="col-lg-5" data-aos="fade-left">
                <div class="contact-info">
                    <!-- Adresse -->
                    <div class="info-box mb-3 p-3 shadow-sm">
                        <h5 class="fw-bold mb-2">📍 Adresse</h5>
                        <p class="mb-0 text-muted">Boulevard latrile <br>Abidjan, Côte d'Ivoire</p>
                    </div>
                    
                    <!-- Téléphone -->
                    <div class="info-box mb-3 p-3 shadow-sm">
                        <h5 class="fw-bold mb-2">📞 Téléphone</h5>
                        <p class="mb-0 text-muted">+225 07 69 47 99 01<br><small class="text-muted">Lundi-Vendredi, 8h-20h</small></p>
                    </div>
                    
                    <!-- Email -->
                    <div class="info-box mb-3 p-3 shadow-sm">
                        <h5 class="fw-bold mb-2">✉️ Email</h5>
                        <p class="mb-0 text-muted">fabrice210804@gmail.com<br>fabrice210804@gmail.com</p>
                    </div>
                    
                    <!-- Heures d'Ouverture (détaillées comme sur l'image) -->
                    <div class="opening-hours-box p-3">
                        <h5 class="fw-bold mb-3">⏰ Heures d'Ouverture</h5>
                        <div class="hours-item d-flex justify-content-between">
                            <span>Lundi - Vendredi</span>
                            <span class="fw-medium">8:00 - 20:00</span>
                        </div>
                        <div class="hours-item d-flex justify-content-between">
                            <span>Samedi</span>
                            <span class="fw-medium">9:00 - 15:00</span>
                        </div>
                        <div class="hours-item d-flex justify-content-between">
                            <span>Dimanche</span>
                            <span class="fw-medium text-danger">Fermé</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Message de succès -->
        @if(session('success'))
            <div class="alert alert-success mt-4">
                {{ session('success') }}
            </div>
        @endif
       

        <!-- MAP -->
        <div class="mt-5" data-aos="zoom-in">
            <<iframe 
                src="https://maps.google.com/maps?q=Abidjan+C%C3%B4te+d%27Ivoire&t=&z=12&ie=UTF8&iwloc=&output=embed"
                style="width:100%; height:400px; border:0; border-radius:10px;">
            </iframe>
        </div>
    </div>
</section>
@endsection