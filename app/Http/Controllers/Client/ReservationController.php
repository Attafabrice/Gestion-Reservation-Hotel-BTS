<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TypeReservation;
use App\Models\Reservation;
use App\Models\Chambre;
use App\Models\Tarif;
use Carbon\Carbon;

class ReservationController extends Controller
{
    // Liste des réservations du client
    public function index(){
        $reservations = Reservation::where('user_id', Auth::id())->with(['chambre', 'chambre.type', 'typeReservation', 'paiements'])->latest()->paginate(10);
        return view('client.reservation.index', compact('reservations'));
    }

    // Formulaire de réservation pour une chambre spécifique
    public function create($id){
        $chambre = Chambre::with('type')->findOrFail($id);
        $types   = TypeReservation::all();
        $tarifs  = Tarif::where('type_chambre_id', $chambre->type_chambre_id)->get();

        // On passe $pending directement à la vue, pas besoin de old()
        $pending = session()->pull('reservation_pending', []);

        return view('client.reservation.create', compact('types', 'chambre', 'tarifs', 'pending'));
    }

    // Dates réservées pour une chambre (AJAX)
    public function datesReservees(Request $request){
        $chambreId = $request->chambre_id;
        if (!$chambreId) {
            return response()->json([]);
        }

        $reservations = Reservation::where('chambre_id', $chambreId)
            ->whereIn('statut', ['confirmee', 'en_attente'])
            ->whereHas('typeReservation', fn($q) => $q->where('libelle', '!=', 'passage'))
            ->get(['date_debut', 'date_fin']);

        $plages = $reservations->map(fn($r) => [
            'from' => Carbon::parse($r->date_debut)->format('Y-m-d'),
            'to'   => Carbon::parse($r->date_fin)->format('Y-m-d'),
        ]);

        return response()->json($plages);
    }

    // Enregistrement
    public function store(Request $request){
    //  Si non connecté : sauvegarder et rediriger vers login
    if (!Auth::check()) {
        session([
            'reservation_pending' => $request->except('_token'),
        ]);

        // Laisser Laravel gérer url.intended proprement
        session()->put('url.intended', route('client.reservation.create', $request->chambre_id));

        return redirect()->route('login')
            ->with('info', 'Connectez-vous pour finaliser votre réservation.');
    }
        $isPassage = $this->estPassage($request->type_reservation_id);

        if ($isPassage) {
            $request->validate([
                'chambre_id'          => 'required|exists:chambres,id',
                'type_reservation_id' => 'required|exists:type_reservations,id',
                'date_debut'          => 'required|date|after_or_equal:today',
                'heure_debut'         => 'required',
                'heure_fin'           => 'required|after:heure_debut',
            ]);

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
                return back()->with('error', 'Aucun tarif disponible pour ce passage.')->withInput();
            }

            Reservation::create([
                'user_id'             => Auth::id(),
                'chambre_id'          => $chambre->id,
                'type_reservation_id' => $request->type_reservation_id,
                'date_debut'          => $request->date_debut,
                'date_fin'            => $request->date_debut,
                'heure_debut'         => $request->heure_debut,
                'heure_fin'           => $request->heure_fin,
                'nombres_heures'      => $nombresHeures,
                'nombre_jours'        => null,
                'prix_total'          => $nombresHeures * $tarif->prix,
                'statut'              => 'en_attente',
            ]);

        } else {
            $request->validate([
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
                return back()->with('error', 'Cette chambre est déjà réservée sur cette période.')->withInput();
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
                return back()->with('error', 'Aucun tarif disponible pour cette combinaison.')->withInput();
            }

            Reservation::create([
                'user_id'             => Auth::id(),
                'chambre_id'          => $chambre->id,
                'type_reservation_id' => $request->type_reservation_id,
                'date_debut'          => $request->date_debut,
                'date_fin'            => $request->date_fin,
                'nombre_jours'        => $jours,
                'nombres_heures'      => null,
                'prix_total'          => $jours * $tarif->prix,
                'statut'              => 'en_attente',
            ]);
        }
        return redirect()->route('client.reservation.index')
            ->with('success', 'Réservation effectuée avec succès ! En attente de confirmation.');
    }

    // Détail
    public function show($id){
        $reservation = Reservation::where('user_id', Auth::id())
            ->with(['chambre', 'chambre.type', 'typeReservation', 'paiements']) 
            ->findOrFail($id);
        return view('client.reservation.show', compact('reservation'));
    }

    // Annuler
    public function annuler($id){
        $reservation = Reservation::where('user_id', Auth::id())
            ->where('statut', 'en_attente')->findOrFail($id);
        $reservation->update(['statut' => 'annulee']);

        return redirect()->route('client.reservation.index')->with('success', 'Réservation annulée avec succès.');
    }

    // Helper
    private function estPassage(?int $typeReservationId): bool{
        if (!$typeReservationId) return false;
        $type = TypeReservation::find($typeReservationId);
        return $type && strtolower(trim($type->libelle)) === 'passage';
    }
}