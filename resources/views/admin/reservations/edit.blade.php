@extends('layouts.admin')

@section('admin-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Modifier la réservation</h2>
        <span class="badge bg-secondary font-monospace">{{ $reservation->code_reservation ?? '#'.$reservation->id }}</span>
    </div>
    <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">

                <form action="{{ route('admin.reservations.update', $reservation->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    {{-- CLIENT --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Client</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" class="form-control bg-light"
                                   value="{{ $reservation->user->nom ?? 'Inconnu' }} {{ $reservation->user->prenom ?? '' }}"
                                   disabled>
                        </div>
                    </div>

                    {{-- CHAMBRE ACTUELLE --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Chambre actuelle</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-door-open"></i>
                            </span>
                            <input type="text" class="form-control bg-light"
                                   value="Chambre {{ $reservation->chambre->numero ?? '—' }}" disabled>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- DATES --}}
                    @php
                        $isPassage = strtolower(trim($reservation->typeReservation->libelle ?? '')) === 'passage';
                    @endphp

                    @if($isPassage)
                    {{-- DATE PASSAGE --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Date</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                            <input type="text" name="date_debut" id="date_debut"
                                   class="form-control" placeholder="Choisir une date"
                                   value="{{ old('date_debut', \Carbon\Carbon::parse($reservation->date_debut)->format('Y-m-d')) }}"
                                   required>
                        </div>
                    </div>

                    {{-- HEURES --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Heure d'arrivée</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                <input type="time" name="heure_debut" id="heure_debut"
                                       class="form-control"
                                       value="{{ old('heure_debut', $reservation->heure_debut) }}"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Heure de départ</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-clock-fill"></i></span>
                                <input type="time" name="heure_fin" id="heure_fin"
                                       class="form-control"
                                       value="{{ old('heure_fin', $reservation->heure_fin) }}"
                                       required>
                            </div>
                        </div>
                    </div>

                    @else
                    {{-- DATES SEJOUR --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date de début</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input type="text" name="date_debut" id="date_debut"
                                       class="form-control" placeholder="Choisir une date"
                                       value="{{ old('date_debut', \Carbon\Carbon::parse($reservation->date_debut)->format('Y-m-d')) }}"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date de fin</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar3-fill"></i></span>
                                <input type="text" name="date_fin" id="date_fin"
                                       class="form-control" placeholder="Choisir une date"
                                       value="{{ old('date_fin', \Carbon\Carbon::parse($reservation->date_fin)->format('Y-m-d')) }}"
                                       required>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- RECAP PRIX --}}
                    <div class="bg-light rounded-3 p-3 mb-4">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="text-muted small">
                                    {{ $isPassage ? 'Heures' : 'Nuits' }}
                                </div>
                                <div class="fw-bold fs-5" id="jours">
                                    {{ $isPassage ? ($reservation->nombres_heures ?? 0) : ($reservation->nombre_jours ?? 0) }}
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-muted small">Prix unitaire</div>
                                <div class="fw-bold fs-5" id="prix">—</div>
                            </div>
                            <div class="col-4">
                                <div class="text-muted small">Total</div>
                                <div class="fw-bold fs-5 text-success" id="total">
                                    {{ number_format($reservation->prix_total) }} FCFA
                                </div>
                            </div>
                        </div>
                        <div id="message_erreur" class="text-danger small mt-2 text-center"></div>
                    </div>

                    {{-- BOUTONS --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Mettre à jour
                        </button>
                        <a href="{{ route('admin.reservations.index') }}" class="btn btn-light px-4">
                            Annuler
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- INFOS DROITE --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-info-circle me-1 text-primary"></i> Infos réservation
                </h6>
                <table class="table table-borderless mb-0 small">
                    <tr>
                        <td class="text-muted ps-0">Type</td>
                        <td class="fw-semibold">{{ $reservation->typeReservation->libelle ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Statut</td>
                        <td>
                            <span class="badge bg-{{ match($reservation->statut) {
                                'en_attente' => 'warning',
                                'confirmee'  => 'success',
                                'annulee'    => 'danger',
                                'terminee'   => 'secondary',
                                default      => 'dark'
                            } }}">
                                {{ ucfirst($reservation->statut) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Créée le</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->created_at)->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Flatpickr --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<script>
    const tarifs = @json($tarifs ?? []);
    const isPassage = {{ $isPassage ? 'true' : 'false' }};

    document.addEventListener('DOMContentLoaded', function () {

        const joursEl   = document.getElementById('jours');
        const prixEl    = document.getElementById('prix');
        const totalEl   = document.getElementById('total');
        const message   = document.getElementById('message_erreur');

        @if($isPassage)
        // ── PASSAGE : flatpickr sur date seule
        flatpickr('#date_debut', {
            locale: 'fr',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            disableMobile: true,
            defaultDate: "{{ \Carbon\Carbon::parse($reservation->date_debut)->format('Y-m-d') }}",
        });

        const heureDebut = document.getElementById('heure_debut');
        const heureFin   = document.getElementById('heure_fin');

        function calculerPassage() {
            message.innerText = '';
            if (!heureDebut.value || !heureFin.value) return;
            const h1     = heureDebut.value.split(':').map(Number);
            const h2     = heureFin.value.split(':').map(Number);
            const heures = ((h2[0]*60+h2[1]) - (h1[0]*60+h1[1])) / 60;
            if (heures <= 0) {
                message.innerText = "L'heure de départ doit être après l'heure d'arrivée.";
                return;
            }
            const tarif = tarifs.find(t =>
                t.type_reservation_id == {{ $reservation->type_reservation_id }}
            );
            if (!tarif) { message.innerText = 'Tarif non défini.'; return; }
            joursEl.innerText = heures.toFixed(1);
            prixEl.innerText  = Number(tarif.prix).toLocaleString() + ' FCFA';
            totalEl.innerText = Number(Math.round(heures * tarif.prix)).toLocaleString() + ' FCFA';
        }

        heureDebut.addEventListener('change', calculerPassage);
        heureFin.addEventListener('change', calculerPassage);
        calculerPassage();

        @else
        // ── SEJOUR : flatpickr sur date début et fin
        let fpDebut = flatpickr('#date_debut', {
            locale: 'fr',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            disableMobile: true,
            defaultDate: "{{ \Carbon\Carbon::parse($reservation->date_debut)->format('Y-m-d') }}",
            onChange: function(dates, dateStr) {
                fpFin.set('minDate', dateStr);
                calculerSejour();
            }
        });

        let fpFin = flatpickr('#date_fin', {
            locale: 'fr',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            disableMobile: true,
            defaultDate: "{{ \Carbon\Carbon::parse($reservation->date_fin)->format('Y-m-d') }}",
            onChange: calculerSejour
        });

        function calculerSejour() {
            message.innerText = '';
            const d1 = document.getElementById('date_debut').value;
            const d2 = document.getElementById('date_fin').value;
            if (!d1 || !d2) return;
            const nuits = (new Date(d2) - new Date(d1)) / (1000 * 60 * 60 * 24);
            if (nuits <= 0) { message.innerText = 'Dates invalides.'; return; }

            const tarif = tarifs.find(t =>
                t.type_reservation_id == {{ $reservation->type_reservation_id }}
            );
            if (!tarif) { message.innerText = 'Tarif non défini.'; return; }

            joursEl.innerText = nuits;
            prixEl.innerText  = Number(tarif.prix).toLocaleString() + ' FCFA';
            totalEl.innerText = Number(nuits * tarif.prix).toLocaleString() + ' FCFA';
        }

        calculerSejour();
        @endif
    });
</script>

@endsection