@extends('layouts.admin')
@section('admin-content')

    {{-- Flatpickr CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

    <h2>Créer une réservation</h2>
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

<form action="{{ route('admin.reservations.store') }}" method="POST">
    @csrf

    {{-- CLIENT --}}
    <div class="mb-3">
        <label>Client</label>
        <select name="user_id" class="form-control" required>
            <option value="">-- Choisir --</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->nom }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- TYPE DE RESERVATION (en premier pour conditionner l'affichage) --}}
    <div class="mb-3">
        <label>Type de réservation</label>
        <select name="type_reservation_id" id="type_reservation" class="form-control" required>
            <option value="">-- Choisir --</option>
            @foreach($types as $type)
                <option value="{{ $type->id }}"
                    data-libelle="{{ strtolower($type->libelle) }}"
                    {{ old('type_reservation_id') == $type->id ? 'selected' : '' }}>
                    {{ $type->libelle }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- DATE DEBUT --}}
    <div class="mb-3">
        <label>Date début</label>
        <input type="text" name="date_debut" id="date_debut" class="form-control"
               placeholder="Choisir une date" value="{{ old('date_debut') }}" required>
    </div>

    {{-- DATE FIN (cachée pour passage) --}}
    <div class="mb-3" id="bloc_date_fin">
        <label>Date fin</label>
        <input type="text" name="date_fin" id="date_fin" class="form-control"
               placeholder="Choisir une date" value="{{ old('date_fin') }}">
    </div>

    {{-- HEURES (visibles uniquement pour passage) --}}
    <div class="mb-3 d-none" id="bloc_heures">
        <div class="row">
            <div class="col-md-6">
                <label>Heure d'arrivée</label>
                <input type="time" name="heure_debut" id="heure_debut" class="form-control"
                       value="{{ old('heure_debut') }}">
            </div>
            <div class="col-md-6">
                <label>Heure de départ</label>
                <input type="time" name="heure_fin" id="heure_fin" class="form-control"
                       value="{{ old('heure_fin') }}">
            </div>
        </div>
    </div>

    {{-- TYPE DE CHAMBRE --}}
    <div class="mb-3">
        <label>Type de chambre</label>
        <select id="type_chambre" name="type_chambre_id" class="form-control" required>
            <option value="">-- Choisir --</option>
            @foreach($datas as $data)
                <option value="{{ $data->id }}" {{ old('type_chambre_id') == $data->id ? 'selected' : '' }}>
                    {{ $data->libelle }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- CHAMBRE --}}
    <div class="mb-3">
        <label>Chambre</label>
        <select name="chambre_id" id="chambre" class="form-control" required>
            <option value="">-- Choisir les dates et le type d'abord --</option>
        </select>
    </div>

    {{-- RECAP PRIX --}}
    <div class="mt-3 p-3 border rounded bg-light">
        <div id="recap_sejour">
            <p class="mb-1">Nuits : <strong id="jours">0</strong></p>
        </div>
        <div id="recap_passage" class="d-none">
            <p class="mb-1">Heures : <strong id="heures_calc">0</strong></p>
        </div>
        <p class="mb-1">Prix unitaire : <strong id="prix">0</strong> F</p>
        <p class="mb-1">Total : <strong id="total">0</strong> F</p>
        <div id="message_erreur" class="text-danger mt-1"></div>
    </div>

    {{-- BOUTONS --}}
    <div class="mt-3">
        <button class="btn btn-primary">Créer</button>
        <a href="{{ route('admin.reservations.index') }}" class="btn btn-light">Annuler</a>
    </div>
</form>

{{-- Flatpickr JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<script>
    const tarifs = @json($tarifs ?? []);
    const urlChambres = "{{ route('admin.reservations.chambres-disponibles') }}";
    const urlDatesRes = "{{ route('admin.reservations.dates-reservees') }}";

    document.addEventListener('DOMContentLoaded', function () {

        const typeReservation = document.getElementById('type_reservation');
        const typeChambre     = document.getElementById('type_chambre');
        const chambreSelect   = document.getElementById('chambre');
        const blocDateFin     = document.getElementById('bloc_date_fin');
        const blocHeures      = document.getElementById('bloc_heures');
        const heureDebut      = document.getElementById('heure_debut');
        const heureFin        = document.getElementById('heure_fin');
        const joursEl         = document.getElementById('jours');
        const heuresEl        = document.getElementById('heures_calc');
        const prixEl          = document.getElementById('prix');
        const totalEl         = document.getElementById('total');
        const message         = document.getElementById('message_erreur');
        const recapSejour     = document.getElementById('recap_sejour');
        const recapPassage    = document.getElementById('recap_passage');

        // ── Flatpickr instances
        let fpDebut = flatpickr('#date_debut', {
            locale: 'fr',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            disableMobile: true,
            onChange: function(dates, dateStr) {
                if (!isPassage()) {
                    fpFin.set('minDate', dateStr);
                }
                triggerUpdate();
            }
        });
        let fpFin = flatpickr('#date_fin', {
            locale: 'fr',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            disableMobile: true,
            onChange: function() {
                triggerUpdate();
            }
        });

        // ── Helpers
        function isPassage() {
            const opt = typeReservation.selectedOptions[0];
            return opt && opt.dataset.libelle === 'passage';
        }
        function getDateDebut() { return document.getElementById('date_debut').value; }
        function getDateFin()   { return document.getElementById('date_fin').value; }

        function resetChambres(msg = '-- Choisir --') {
            chambreSelect.innerHTML = `<option value="">${msg}</option>`;
        }

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
                
                // Synchronise date_fin = date_debut
                if (getDateDebut()) {
                    fpFin.setDate(getDateDebut());
                }
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

        // ── Chargement des chambres disponibles
        function loadChambres() {
            const dateDebut = getDateDebut();
            const passage   = isPassage();

            if (!dateDebut || !typeChambre.value) {
                resetChambres('-- Choisir les dates et le type d\'abord --');
                return;
            }
            if (!passage && !getDateFin()) {
                resetChambres('-- Choisir les dates d\'abord --');
                return;
            }
            if (!passage && getDateFin() <= dateDebut) {
                resetChambres('-- Dates invalides --');
                return;
            }

            chambreSelect.innerHTML = `<option>Chargement...</option>`;

            let url = `${urlChambres}?date_debut=${dateDebut}&type_chambre_id=${typeChambre.value}&is_passage=${passage ? 1 : 0}`;
            if (!passage) url += `&date_fin=${getDateFin()}`;

            fetch(url)
                .then(res => {
                    if (!res.ok) throw new Error(`Erreur ${res.status}`);
                    return res.json();
                })
                .then(data => {
                    chambreSelect.innerHTML = `<option value="">-- Choisir --</option>`;
                    if (!data.length) {
                        resetChambres('Aucune chambre disponible');
                        return;
                    }
                    data.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id;
                        opt.textContent = `Chambre ${c.numero}`;
                        chambreSelect.appendChild(opt);
                    });
                })
                .catch(() => resetChambres('Erreur de chargement'));
        }

        // ── Griser les dates réservées selon la chambre choisie
        function updateDatesGrisees() {
            const chambreId = chambreSelect.value;
            if (!chambreId) {
                fpDebut.set('disable', []);
                fpFin.set('disable', []);
                return;
            }

            fetch(`${urlDatesRes}?chambre_id=${chambreId}`)
                .then(res => res.json())
                .then(plages => {
                    // Flatpickr accepte des plages {from, to}
                    fpDebut.set('disable', plages);
                    fpFin.set('disable', plages);
                })
                .catch(() => {});
        }

        // ── Calcul du prix
        function calculerPrix() {
            message.innerText = '';

            if (!typeChambre.value || !typeReservation.value) return;

            const tarif = tarifs.find(t =>
                t.type_chambre_id == typeChambre.value &&
                t.type_reservation_id == typeReservation.value
            );

            if (!tarif) {
                message.innerText = 'Tarif non défini pour cette combinaison.';
                prixEl.innerText = '0';
                totalEl.innerText = '0';
                return;
            }

            if (isPassage()) {
                if (!heureDebut.value || !heureFin.value) return;

                const h1    = heureDebut.value.split(':').map(Number);
                const h2    = heureFin.value.split(':').map(Number);
                const diff  = (h2[0] * 60 + h2[1]) - (h1[0] * 60 + h1[1]);
                const heures = diff / 60;

                if (heures <= 0) {
                    message.innerText = 'L\'heure de départ doit être après l\'heure d\'arrivée.';
                    return;
                }

                heuresEl.innerText  = heures.toFixed(1);
                prixEl.innerText    = tarif.prix;
                totalEl.innerText   = Math.round(heures * tarif.prix);

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
                prixEl.innerText   = tarif.prix;
                totalEl.innerText  = nuits * tarif.prix;
            }
        }

        // ── Déclencheur global
        function triggerUpdate() {
            if (isPassage()) {
                fpFin.setDate(getDateDebut());
            }
            loadChambres();
            calculerPrix();
        }

        // ── Events
        typeReservation.addEventListener('change', () => {
            toggleModePassage();
            triggerUpdate();
        });

        typeChambre.addEventListener('change', triggerUpdate);

        chambreSelect.addEventListener('change', () => {
            updateDatesGrisees();
            calculerPrix();
        });

        heureDebut.addEventListener('change', calculerPrix);
        heureFin.addEventListener('change', calculerPrix);

        // Init
        toggleModePassage();
    });
</script>
@endsection