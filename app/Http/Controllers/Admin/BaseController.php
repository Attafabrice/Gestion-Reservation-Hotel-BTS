<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Contact;
use Illuminate\Support\Facades\View;

class BaseController extends Controller
{
    //Pour l'injecter afin de gerer les notifications

    public function __construct(){
        // Via middleware pour exécuter après que tout soit initialisér
        $this->middleware(function ($request, $next) {

            View::share('reservationsEnAttente',
                Reservation::with(['user', 'chambre'])
                    ->where('statut', 'en_attente')
                    ->latest()
                    ->take(5)
                    ->get()
            );

            View::share('messagesNonLus',
                Contact::where('lu', false)
                    ->latest()
                    ->take(5)
                    ->get()
            );

            View::share('totalNotifications',
                Reservation::where('statut', 'en_attente')->count() +
                Contact::where('lu', false)->count()
            );

            return $next($request);
        });
    }
}