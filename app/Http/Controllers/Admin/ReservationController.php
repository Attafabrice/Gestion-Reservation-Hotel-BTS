<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TypeReservation;
use App\Models\TypeChambre;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Chambre;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends BaseController
{
   public function index(Request $request){
    $query = Reservation::with(['user', 'chambre', 'typeReservation'])->latest();

    if ($request->filled('search_code')) {
        $query->where('code_reservation', 'like', "%{$request->search_code}%");
    }

    if ($request->filled('search_nom') || $request->filled('search_prenom')) {
        $query->whereHas('user', function($u) use ($request) {
            $u->where(function($q) use ($request) {
                if ($request->filled('search_nom')) {
                    $q->where('nom', 'like', "%{$request->search_nom}%");
                }
                if ($request->filled('search_prenom')) {
                    $q->where('prenom', 'like', "%{$request->search_prenom}%");
                }
            });
        });
    }
    if ($request->filled('search_date_debut')) {
        $query->whereDate('date_debut', '>=', $request->search_date_debut);
    }
    if ($request->filled('search_date_fin')) {
        $query->whereDate('date_fin', '<=', $request->search_date_fin);
    }

    $reservations = $query->paginate(10)->withQueryString();
    return view('admin.reservations.index', compact('reservations'));
    }

    // Formulaire de création
    public function create(){
        $users  = User::all();
        $datas  = TypeChambre::all();
        $types  = TypeReservation::all();
        $tarifs = Tarif::all();
        return view('admin.reservations.create', compact('users', 'tarifs', 'datas', 'types'));
    }

    // Chambres disponibles (AJAX)
    public function chambresDisponibles(Request $request){
        $dateDebut   = $request->date_debut;
        $dateFin     = $request->date_fin;
        $typeChambre = $request->type_chambre_id;
        $isPassage   = $request->boolean('is_passage');

        if (!$dateDebut || !$typeChambre) {
            return response()->json([]);
        }

        // Pour passage : date_fin = date_debut
        if ($isPassage) {
            $dateFin = $dateDebut;
        }

        if (!$dateFin || (!$isPassage && $dateFin <= $dateDebut)) {
            return response()->json([]);
        }

        if ($isPassage) {
            // Exclure chambres avec un séjour qui couvre ce jour
            $chambresOccupees = Reservation::whereIn('statut', ['confirmee', 'en_attente'])
                ->where('date_debut', '<=', $dateDebut)
                ->where('date_fin', '>=', $dateDebut)
                ->whereHas('typeReservation', fn($q) => $q->where('libelle', '!=', 'passage'))
                ->pluck('chambre_id');
        } else {
            // Exclure chambres avec un chevauchement de dates
            $chambresOccupees = Reservation::whereIn('statut', ['confirmee', 'en_attente'])
                ->where('date_debut', '<', $dateFin)
                ->where('date_fin', '>', $dateDebut)
                ->pluck('chambre_id');
        }

        $chambres = Chambre::where('type_chambre_id', $typeChambre)
            ->whereNotIn('id', $chambresOccupees)
            ->get(['id', 'numero']);

        return response()->json($chambres);
    }

    // Dates réservées pour une chambre (AJAX — pour griser le calendrier)
    public function datesReservees(Request $request){
        $chambreId = $request->chambre_id;

        if (!$chambreId) {
            return response()->json([]);
        }
        $reservations = Reservation::where('chambre_id', $chambreId)
            ->whereIn('statut', ['confirmee', 'en_attente'])
            ->whereHas('typeReservation', fn($q) => $q->where('libelle', '!=', 'passage'))
            ->get(['date_debut', 'date_fin']);

        $plages = [];
        foreach ($reservations as $res) {
            $plages[] = [
                'from' => Carbon::parse($res->date_debut)->format('Y-m-d'),
                'to'   => Carbon::parse($res->date_fin)->format('Y-m-d'),
            ];
        }
        return response()->json($plages);
    }

    // Enregistrement
    public function store(Request $request){
        $isPassage = $this->estPassage($request->type_reservation_id);

        if ($isPassage) {
            $request->validate([
                'user_id'             => 'required|exists:users,id',
                'chambre_id'          => 'required|exists:chambres,id',
                'type_reservation_id' => 'required|exists:type_reservations,id',
                'date_debut'          => 'required|date|after_or_equal:today',
                'heure_debut'         => 'required',
                'heure_fin'           => 'required|after:heure_debut',
            ]);

            // Conflit passage : même jour, heures qui se chevauchent
            $conflit = Reservation::where('chambre_id', $request->chambre_id)
                ->whereIn('statut', ['confirmee', 'en_attente'])
                ->where('date_debut', $request->date_debut)
                ->whereHas('typeReservation', fn($q) => $q->where('libelle', 'passage'))
                ->where('heure_debut', '<', $request->heure_fin)
                ->where('heure_fin',   '>', $request->heure_debut)
                ->exists();

            if ($conflit) {
                return back()->withErrors(['heure_debut' => 'Cette chambre est déjà réservée sur ce créneau horaire.'])->withInput();
            }

            // Conflit séjour sur ce jour
            $conflitSejour = Reservation::where('chambre_id', $request->chambre_id)
                ->whereIn('statut', ['confirmee', 'en_attente'])
                ->where('date_debut', '<=', $request->date_debut)
                ->where('date_fin',   '>=', $request->date_debut)
                ->whereHas('typeReservation', fn($q) => $q->where('libelle', '!=', 'passage'))
                ->exists();

            if ($conflitSejour) {
                return back()->withErrors(['chambre_id' => 'Cette chambre est occupée (séjour) à cette date.'])->withInput();
            }

            $heureDebut    = Carbon::parse($request->heure_debut);
            $heureFin      = Carbon::parse($request->heure_fin);
            $nombresHeures = $heureFin->diffInHours($heureDebut);

            if ($nombresHeures <= 0) {
                return back()->withErrors(['heure_fin' => 'La durée doit être d\'au moins 1 heure.'])->withInput();
            }

            $chambre = Chambre::findOrFail($request->chambre_id);
            $tarif   = Tarif::where('type_chambre_id', $chambre->type_chambre_id)
                ->where('type_reservation_id', $request->type_reservation_id)
                ->first();

            if (!$tarif) {
                return back()->with('error', 'Aucun tarif défini pour ce passage.')->withInput();
            }

            $prixTotal = $nombresHeures * $tarif->prix;

            Reservation::create([
                'user_id'             => $request->user_id,
                'chambre_id'          => $request->chambre_id,
                'type_reservation_id' => $request->type_reservation_id,
                'date_debut'          => $request->date_debut,
                'date_fin'            => $request->date_debut, // même jour
                'heure_debut'         => $request->heure_debut,
                'heure_fin'           => $request->heure_fin,
                'nombres_heures'      => $nombresHeures,
                'nombre_jours'        => null,
                'prix_total'          => $prixTotal,
                'statut'              => 'en_attente',
            ]);

        } else {
            $request->validate([
                'user_id'             => 'required|exists:users,id',
                'chambre_id'          => 'required|exists:chambres,id',
                'type_reservation_id' => 'required|exists:type_reservations,id',
                'date_debut'          => 'required|date|after_or_equal:today',
                'date_fin'            => 'required|date|after:date_debut',
            ]);

            $conflit = Reservation::where('chambre_id', $request->chambre_id)
                ->whereIn('statut', ['confirmee', 'en_attente'])
                ->where('date_debut', '<', $request->date_fin)
                ->where('date_fin',   '>', $request->date_debut)
                ->exists();

            if ($conflit) {
                return back()->withErrors(['chambre_id' => 'Cette chambre est déjà réservée sur cette période.'])->withInput();
            }

            $jours = Carbon::parse($request->date_debut)->diffInDays(Carbon::parse($request->date_fin));

            if ($jours <= 0) {
                return back()->withErrors(['date_fin' => 'La durée doit être d\'au moins 1 jour.'])->withInput();
            }

            $chambre = Chambre::findOrFail($request->chambre_id);
            $tarif   = Tarif::where('type_chambre_id', $chambre->type_chambre_id)
                ->where('type_reservation_id', $request->type_reservation_id)
                ->first();

            if (!$tarif) {
                return back()->with('error', 'Aucun tarif défini pour cette combinaison.')->withInput();
            }

            $prixTotal = $jours * $tarif->prix;

            Reservation::create([
                'user_id'             => $request->user_id,
                'chambre_id'          => $request->chambre_id,
                'type_reservation_id' => $request->type_reservation_id,
                'date_debut'          => $request->date_debut,
                'date_fin'            => $request->date_fin,
                'nombre_jours'        => $jours,
                'nombres_heures'      => null,
                'prix_total'          => $prixTotal,
                'statut'              => 'en_attente',
            ]);
        }

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Réservation créée avec succès.');
    }

    // Détail
    public function show(Reservation $reservation){
        $reservation->load(['user', 'chambre', 'typeReservation']);
        return view('admin.reservations.show', compact('reservation'));
    }

    // Formulaire modification
    public function edit(Reservation $reservation){
        if (in_array($reservation->statut, ['annulee', 'terminee'])) {
            return redirect()->route('admin.reservations.index')
                ->with('error', 'Impossible de modifier une réservation annulée ou terminée.');
        }

        $reservation->load(['user', 'chambre', 'typeReservation']);
        $users    = User::all();
        $datas    = TypeChambre::all();
        $types    = TypeReservation::all();
        $tarifs   = Tarif::all();
        $chambres = Chambre::all();

        return view('admin.reservations.edit', compact(
            'reservation', 'users', 'datas', 'types', 'tarifs', 'chambres'
        ));
    }

    // Mise à jour
    public function update(Request $request, Reservation $reservation){
        if (in_array($reservation->statut, ['annulee', 'terminee'])) {
            return back()->with('error', 'Impossible de modifier une réservation annulée ou terminée.');
        }

        $isPassage = $this->estPassage($reservation->type_reservation_id);
        if ($isPassage) {
            $request->validate([
                'date_debut'  => 'required|date|after_or_equal:today',
                'heure_debut' => 'required',
                'heure_fin'   => 'required|after:heure_debut',
            ]);

            $conflit = Reservation::where('chambre_id', $reservation->chambre_id)
                ->where('id', '!=', $reservation->id)
                ->whereIn('statut', ['confirmee', 'en_attente'])
                ->where('date_debut', $request->date_debut)
                ->whereHas('typeReservation', fn($q) => $q->where('libelle', 'passage'))
                ->where('heure_debut', '<', $request->heure_fin)
                ->where('heure_fin',   '>', $request->heure_debut)
                ->exists();

            if ($conflit) {
                return back()->withErrors(['heure_debut' => 'Créneau horaire déjà réservé.'])->withInput();
            }
            $nombresHeures = Carbon::parse($request->heure_fin)->diffInHours(Carbon::parse($request->heure_debut));
            $tarif = Tarif::where('type_chambre_id', $reservation->chambre->type_chambre_id)->where('type_reservation_id', $reservation->type_reservation_id)
                    ->first();

            if (!$tarif) {
                return back()->with('error', 'Aucun tarif défini.')->withInput();
            }
            $reservation->update([
                'date_debut'     => $request->date_debut,
                'date_fin'       => $request->date_debut,
                'heure_debut'    => $request->heure_debut,
                'heure_fin'      => $request->heure_fin,
                'nombres_heures' => $nombresHeures,
                'prix_total'     => $nombresHeures * $tarif->prix,
            ]);

        } else {
            $request->validate([
                'date_debut' => 'required|date|after_or_equal:today',
                'date_fin'   => 'required|date|after:date_debut',
            ]);

            $conflit = Reservation::where('chambre_id', $reservation->chambre_id)
                ->where('id', '!=', $reservation->id)
                ->whereIn('statut', ['confirmee', 'en_attente'])
                ->where('date_debut', '<', $request->date_fin)
                ->where('date_fin',   '>', $request->date_debut)->exists();
            if ($conflit) {
                return back()->with('error', 'Cette chambre est déjà réservée sur cette période.')->withInput();
            }

            $jours = Carbon::parse($request->date_debut)->diffInDays(Carbon::parse($request->date_fin));
            $tarif = Tarif::where('type_chambre_id', $reservation->chambre->type_chambre_id)->where('type_reservation_id', $reservation->type_reservation_id)
                    ->first();
            if (!$tarif) {
                return back()->with('error', 'Aucun tarif défini.')->withInput();
            }
            $reservation->update([
                'date_debut'  => $request->date_debut,
                'date_fin'    => $request->date_fin,
                'nombre_jours'=> $jours,
                'prix_total'  => $jours * $tarif->prix,
            ]);
        }
        return redirect()->route('admin.reservations.index')->with('success', 'Réservation mise à jour avec succès.');
    }

   // Confirmer → chambre devient occupée
    public function confirmer(Reservation $reservation){
        if ($reservation->statut !== 'en_attente') {
            return back()->with('error', 'Seules les réservations en attente peuvent être confirmées.');
        }
        $reservation->update(['statut' => 'confirmee']);

        // Chambre occupée
        $reservation->chambre->update(['statut' => 'occupee']);
        return back()->with('success', 'Réservation confirmée.');
    }

    // Annuler → chambre redevient disponible
    public function annuler(Reservation $reservation){
        if (!in_array($reservation->statut, ['en_attente', 'confirmee'])) {
            return back()->with('error', 'Cette réservation ne peut pas être annulée.');
        }
        $reservation->update(['statut' => 'annulee']);

        // Chambre disponible seulement si aucune autre réservation active sur cette chambre
        $autreReservationActive = Reservation::where('chambre_id', $reservation->chambre_id)
            ->where('id', '!=', $reservation->id)
            ->whereIn('statut', ['confirmee', 'en_attente'])
            ->exists();

        if (!$autreReservationActive) {
            $reservation->chambre->update(['statut' => 'disponible']);
        }
        return back()->with('success', 'Réservation annulée.');
    }

   // Terminer → chambre redevient disponible
    public function terminer(Reservation $reservation){
        if ($reservation->statut !== 'confirmee') {
            return back()->with('error', 'Seules les réservations confirmées peuvent être terminées.');
        }
        $reservation->update(['statut' => 'terminee']);

        // Chambre disponible seulement si aucune autre réservation active sur cette chambre
        $autreReservationActive = Reservation::where('chambre_id', $reservation->chambre_id)
            ->where('id', '!=', $reservation->id)
            ->whereIn('statut', ['confirmee', 'en_attente'])
            ->exists();

        if (!$autreReservationActive) {
            $reservation->chambre->update(['statut' => 'confirmee']);
        }

        return back()->with('success', 'Réservation terminée.');
    }

    // Supprimer
    public function destroy(Reservation $reservation){
        if ($reservation->statut === 'confirmee') {
            return back()->with('error', 'Impossible de supprimer une réservation confirmée.');
        }
        $reservation->delete();
        return redirect()->route('admin.reservations.index')->with('success', 'Réservation supprimée avec succès.');
    }

    // Helper privé : vérifie si un type_reservation_id correspond à "passage"
    private function estPassage(?int $typeReservationId): bool{
        if (!$typeReservationId) return false;
        $type = TypeReservation::find($typeReservationId);
        return $type && strtolower($type->libelle) === 'passage';
    }

    public function recuPdf(Reservation $reservation){
        $reservation->load(['user', 'chambre', 'typeReservation', 'paiements']);
        $pdf = Pdf::loadView('admin.reservations.recu-pdf', compact('reservation'));
        return $pdf->download('RESERVATION-' . $reservation->code_reservation . '.pdf');
    }
}