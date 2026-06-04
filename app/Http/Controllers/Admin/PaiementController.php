<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paiement;

class PaiementController extends BaseController
{
    // Liste des paiements
    public function index(){
        $paiements = Paiement::with(['reservation.chambre', 'reservation.user', 'reservation.typeReservation'])
            ->latest()->paginate(10);
        return view('admin.paiements.index', compact('paiements'));
    }

    // Détail d'un paiement
    public function show($id){
        $paiement = Paiement::with(['reservation.chambre', 'reservation.user', 'reservation.typeReservation'])
            ->findOrFail($id);
        return view('admin.paiements.show', compact('paiement'));
    }
}