<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\TypeChambre;
use App\Models\Tarif;
use Illuminate\Http\Request;
use App\Models\Chambre;

class RoomController extends Controller
{
    //page index de toutes les chambres(gallery)

   public function index(Request $request){
        $query = Chambre::with('type');
        // capacité
        if ($request->filled('capacite')) {
            $query->where('capacite', '>=', $request->capacite);
        }

        // type chambre
        if ($request->filled('type')) {
            $query->where('type_chambre_id', $request->type);
        }
        
        $chambres = $query->latest()->paginate(9);
        $types = TypeChambre::all();
        return view('client.gallery', compact('chambres', 'types'));
    }   

    //fonction detail
    public function show($id){
        // Charger la chambre + son type
        $chambre = Chambre::with('type')->findOrFail($id);
       return view('client.room.room-details', compact('chambre'));
    }
}