<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chambre;

class AccueilController extends Controller
{
    /**
     * Affiche la page d'accueil avec les chambres et statistiques.
     */
    public function index(): \Illuminate\View\View
    {
        // Récupère les 6 dernières chambres avec leurs relations
        $chambres = Chambre::with(['type',])->latest()->take(6)->get();

        // Statistiques dynamiques
        $stats = [
            'luxuryRooms' => Chambre::count(),
            'starRating' => 5,
            'hourService' => 24,
            'guestSatisfaction' => 98,
        ];

        return view('client.accueil', compact('chambres', 'stats'));
    }
}