<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Chambre;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseController
{
    public function index()
    {
        $totalUsers        = User::count();
        $totalChambres     = Chambre::count();
        $totalReservations = Reservation::count();

        // ✅ Calcul dynamique basé sur les réservations actives
        $chambresOccupeesIds  = Reservation::whereIn('statut', ['confirmee', 'en_attente'])
            ->pluck('chambre_id')
            ->unique();
        $chambresOccupees     = $chambresOccupeesIds->count();
        $chambresDisponibles  = $totalChambres - $chambresOccupees;

        // ✅ Nom cohérent avec la vue et le compact()
        $nbReservationsEnAttente = Reservation::where('statut', 'en_attente')->count();
        $revenusTotal            = Reservation::where('statut', 'terminee')->sum('prix_total');

        $dernieresReservations = Reservation::with(['user', 'chambre', 'typeReservation'])
            ->latest()
            ->take(5)
            ->get();

        $reservationsParMois = Reservation::select(
                DB::raw('MONTH(created_at) as mois'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois');

        $revenusParMois = Reservation::where('statut', 'terminee')
            ->select(
                DB::raw('MONTH(created_at) as mois'),
                DB::raw('SUM(prix_total) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois');

        $moisLabels        = ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Août','Sep','Oct','Nov','Déc'];
        $reservationsDatas = [];
        $revenusDatas      = [];

        for ($i = 1; $i <= 12; $i++) {
            $reservationsDatas[] = $reservationsParMois[$i] ?? 0;
            $revenusDatas[]      = $revenusParMois[$i] ?? 0;
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalChambres',
            'totalReservations',
            'chambresDisponibles',
            'chambresOccupees',
            'revenusTotal',
            'nbReservationsEnAttente', // ✅ correspond à la variable déclarée
            'dernieresReservations',
            'moisLabels',
            'reservationsDatas',
            'revenusDatas'
        ));
    }
}