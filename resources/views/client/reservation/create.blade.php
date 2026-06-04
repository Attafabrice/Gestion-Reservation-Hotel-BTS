@extends('layouts.client')

@section('client-content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

<div class="container py-5">
    <div class="row">

        {{-- INFO CHAMBRE --}}
        <div class="col-lg-5">
            <div class="card shadow">
                <img src="{{ asset('storage/' . $chambre->image) }}" class="img-fluid">
                <div class="p-3">
                    <h4>{{ $chambre->type->libelle }}</h4>
                    <p>{{ $chambre->description }}</p>
                </div>
            </div>
        </div>

        {{-- FORMULAIRE --}}
        <div class="col-lg-7">
            <div class="card shadow p-4">
                <h3 class="mb-4">Réserver cette chambre</h3>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('client.reservation.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="chambre_id" value="{{ $chambre->id }}">
                    <input type="hidden" id="type_chambre_id" value="{{ $chambre->type_chambre_id }}">

                    {{-- TYPE DE CHAMBRE (lecture seule) --}}
                    <div class="mb-3">
                        <label>Type de chambre</label>
                        <input type="text" class="form-control"
                               value="{{ $chambre->type->libelle }}" readonly>
                    </div>

                    {{-- TYPE DE RESERVATION --}}
                    <div class="mb-3">
                        <label>Type de réservation</label>
                        <select name="type_reservation_id" id="type_reservation" class="form-control" required>
                            <option value="">-- Choisir --</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}"
                                    data-libelle="{{ strtolower($type->libelle) }}"
                                    {{$pending['type_reservation_id'] ?? old('type_reservation_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- DATE DEBUT --}}
                    <div class="mb-3">
                        <label>Date</label>
                        <input type="text" name="date_debut" id="date_debut"
                               class="form-control" placeholder="Choisir une date"
                               value="{{ $pending['date_debut'] ?? old('date_debut') }}" required>
                    </div>

                    {{-- DATE FIN (cachée pour passage) --}}
                    <div class="mb-3" id="bloc_date_fin">
                        <label>Date fin</label>
                        <input type="text" name="date_fin" id="date_fin"
                               class="form-control" placeholder="Choisir une date"
                               value="{{$pending['date_fin'] ?? old('date_fin') }}">
                    </div>

                    {{-- HEURES (visibles uniquement pour passage) --}}
                    <div class="mb-3 d-none" id="bloc_heures">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Heure d'arrivée</label>
                                <input type="time" name="heure_debut" id="heure_debut"
                                       class="form-control" value="{{ $pending['heure_debut'] ?? old('heure_debut') }}">
                            </div>
                            <div class="col-md-6">
                                <label>Heure de départ</label>
                                <input type="time" name="heure_fin" id="heure_fin"
                                       class="form-control" value="{{ $pending['heure_fin'] ?? old('heure_fin') }}">
                            </div>
                        </div>
                    </div>

                    {{-- RECAP PRIX --}}
                    <div class="bg-light p-3 rounded mb-3">
                        <div id="recap_sejour">
                            <p class="mb-1">Nuits : <strong id="jours">0</strong></p>
                        </div>
                        <div id="recap_passage" class="d-none">
                            <p class="mb-1">Heures : <strong id="heures_calc">0</strong></p>
                        </div>
                        <p class="mb-1">Prix unitaire : <strong id="prix">0</strong> FCFA</p>
                        <p class="mb-1">Total : <strong id="total">0</strong> FCFA</p>
                        <div id="message_erreur" class="text-danger mt-1"></div>
                    </div>
                   @auth
                        <button type="submit" class="detail-link">
                            Confirmer la réservation
                        </button>
                    @else
                        <div class="alert alert-warning d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span>Vous devez être connecté pour confirmer votre réservation.</span>
                        </div>
                        <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="detail-link text-center d-block">
                            Se connecter pour réserver
                        </a>
                    @endauth
                </form>
            </div>
        </div>
    </div>
</div>
    {{-- Juste après @section('client-content') --}}
   
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<script>
    const tarifs      = @json($tarifs);
    const chambreId   = {{ $chambre->id }};
    const urlDatesRes = "{{ route('client.reservation.dates-reservees') }}";

    // ✅ Valeurs injectées depuis PHP — priorité à $pending (retour login), sinon old() (erreur validation)
    const valDateDebut  = "{{ $pending['date_debut']          ?? old('date_debut') }}";
    const valDateFin    = "{{ $pending['date_fin']            ?? old('date_fin') }}";
    const valTypeRes    = "{{ $pending['type_reservation_id'] ?? old('type_reservation_id') }}";
    const valHeureDebut = "{{ $pending['heure_debut']         ?? old('heure_debut') }}";
    const valHeureFin   = "{{ $pending['heure_fin']           ?? old('heure_fin') }}";

    document.addEventListener('DOMContentLoaded', function () {

        const typeReservation = document.getElementById('type_reservation');
        const heureDebut      = document.getElementById('heure_debut');
        const heureFin        = document.getElementById('heure_fin');
        const blocDateFin     = document.getElementById('bloc_date_fin');
        const blocHeures      = document.getElementById('bloc_heures');
        const joursEl         = document.getElementById('jours');
        const heuresEl        = document.getElementById('heures_calc');
        const prixEl          = document.getElementById('prix');
        const totalEl         = document.getElementById('total');
        const message         = document.getElementById('message_erreur');
        const recapSejour     = document.getElementById('recap_sejour');
        const recapPassage    = document.getElementById('recap_passage');

        // ✅ Pré-remplir le select type_reservation
        if (valTypeRes) {
            typeReservation.value = valTypeRes;
        }

        // ✅ Pré-remplir les heures
        if (valHeureDebut) heureDebut.value = valHeureDebut;
        if (valHeureFin)   heureFin.value   = valHeureFin;

        // ── Flatpickr
        let fpDebut = flatpickr('#date_debut', {
            locale: 'fr',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            disableMobile: true,
            defaultDate: valDateDebut || null, // ✅
            onChange: function(dates, dateStr) {
                if (!isPassage()) {
                    fpFin.set('minDate', dateStr);
                }
                calculerPrix();
            }
        });

        let fpFin = flatpickr('#date_fin', {
            locale: 'fr',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            disableMobile: true,
            defaultDate: valDateFin || null, // ✅
            onChange: calculerPrix
        });

        // ── Charger et griser les dates réservées
        fetch(`${urlDatesRes}?chambre_id=${chambreId}`)
            .then(res => res.json())
            .then(plages => {
                fpDebut.set('disable', plages);
                fpFin.set('disable', plages);
            })
            .catch(() => {});

        // ── Helpers
        function isPassage() {
            const opt = typeReservation.selectedOptions[0];
            return opt && opt.dataset.libelle === 'passage';
        }
        function getDateDebut() { return document.getElementById('date_debut').value; }
        function getDateFin()   { return document.getElementById('date_fin').value; }

        // ── Affichage conditionnel passage / séjour
        function toggleModePassage() {
            if (isPassage()) {
                blocDateFin.classList.add('d-none');
                blocHeures.classList.remove('d-none');
                recapSejour.classList.add('d-none');
                recapPassage.classList.remove('d-none');
                document.querySelector('[name="date_fin"]').removeAttribute('required');
                heureDebut.setAttribute('required', 'required');
                heureFin.setAttribute('required', 'required');
                if (getDateDebut()) fpFin.setDate(getDateDebut());
            } else {
                blocDateFin.classList.remove('d-none');
                blocHeures.classList.add('d-none');
                recapSejour.classList.remove('d-none');
                recapPassage.classList.add('d-none');
                document.querySelector('[name="date_fin"]').setAttribute('required', 'required');
                heureDebut.removeAttribute('required');
                heureFin.removeAttribute('required');
            }
        }

        // ── Calcul du prix
        function calculerPrix() {
            message.innerText = '';
            if (!typeReservation.value) return;
            const tarif = tarifs.find(t => t.type_reservation_id == typeReservation.value);
            if (!tarif) {
                message.innerText = 'Tarif non disponible pour cette combinaison.';
                return;
            }
            if (isPassage()) {
                if (!heureDebut.value || !heureFin.value) return;
                const h1     = heureDebut.value.split(':').map(Number);
                const h2     = heureFin.value.split(':').map(Number);
                const heures = ((h2[0] * 60 + h2[1]) - (h1[0] * 60 + h1[1])) / 60;

                if (heures <= 0) {
                    message.innerText = "L'heure de départ doit être après l'heure d'arrivée.";
                    return;
                }
                heuresEl.innerText = heures.toFixed(1);
                prixEl.innerText   = Number(tarif.prix).toLocaleString();
                totalEl.innerText  = Number(Math.round(heures * tarif.prix)).toLocaleString();

            } else {
                const dateDebut = getDateDebut();
                const dateFin   = getDateFin();
                if (!dateDebut || !dateFin) return;

                const nuits = (new Date(dateFin) - new Date(dateDebut)) / (1000 * 60 * 60 * 24);
                if (nuits <= 0) {
                    message.innerText = 'Dates invalides.';
                    return;
                }
                joursEl.innerText  = nuits;
                prixEl.innerText   = Number(tarif.prix).toLocaleString();
                totalEl.innerText  = Number(nuits * tarif.prix).toLocaleString();
            }
        }

        // ── Events
        typeReservation.addEventListener('change', () => {
            toggleModePassage();
            calculerPrix();
        });
        heureDebut.addEventListener('change', calculerPrix);
        heureFin.addEventListener('change', calculerPrix);

        // ── Init ✅ toggleModePassage en premier pour afficher les bons blocs,
        //          puis calculerPrix pour afficher le total si tout est pré-rempli
        toggleModePassage();
        calculerPrix();
    });
</script>
@endsection