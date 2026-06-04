<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Paiement;
use Barryvdh\DomPDF\Facade\Pdf;

class PaiementController extends Controller
{
    public function show($id){
        $reservation = Reservation::with(['chambre', 'typeReservation'])
            ->where('user_id', Auth::id())->findOrFail($id);

        if ($reservation->statut !== 'confirmee') {
            return redirect()->route('client.reservation.index')
                ->with('error', 'Seules les réservations confirmées peuvent être payées.');
        }

        $dejaPaye = Paiement::where('reservation_id', $id)->where('statut', 'paye')->exists();
        if ($dejaPaye) {
            return redirect()->route('client.reservation.index')
                ->with('error', 'Cette réservation a déjà été payée.');
        }
        return view('client.paiement.create', compact('reservation'));
    }

    public function store(Request $request, $id){
        $request->validate([
            'mode_paiement' => 'required|in:especes,carte,mobile',
        ]);

        $reservation = Reservation::with(['chambre', 'typeReservation'])
            ->where('user_id', Auth::id())->findOrFail($id);

        if ($reservation->statut !== 'confirmee') {
            return back()->with('error', 'Seules les réservations confirmées peuvent être payées.');
        }

        $dejaPaye = Paiement::where('reservation_id', $id)->where('statut', 'paye')->exists();
        if ($dejaPaye) {
            return back()->with('error', 'Cette réservation a déjà été payée.');
        }

        // Capturer l'objet créé
        $paiement = Paiement::create([
            'reservation_id'        => $reservation->id,
            'montant'               => $reservation->prix_total,
            'mode_paiement'         => $request->mode_paiement,
            'statut'                => 'paye',
            'reference_transaction' => 'PAY-' . strtoupper(uniqid()),
            'date_paiement'         => now(),
        ]);

        // Rediriger vers le reçu
        return redirect()->route('client.paiement.recu', $paiement->id)
                ->with('success', 'Paiement effectué avec succès !');
    }

    // Page reçu web
    public function recu($id){
        $paiement = Paiement::with([
            'reservation',
            'reservation.chambre',
            'reservation.typeReservation',
            'reservation.user',
        ])->findOrFail($id);

        if ($paiement->reservation->user_id !== Auth::id()) {
            abort(403);
        }

        return view('client.paiement.recu', compact('paiement'));
    }

    // Télécharger reçu PDF
    public function recuPdf($id){
        $paiement = Paiement::with([
            'reservation',
            'reservation.chambre',
            'reservation.typeReservation',
            'reservation.user',
        ])->findOrFail($id);

        if ($paiement->reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $pdf = Pdf::loadView('client.paiement.recu-pdf', compact('paiement'));

        return $pdf->download('RECU-' . $paiement->reference_transaction . '.pdf');
    }
    
    public function success(){
        return view('client.paiement.success');
    }
}