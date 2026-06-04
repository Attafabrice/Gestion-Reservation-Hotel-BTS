<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TypeReservation;
use Illuminate\Http\Request;

class TypeReservationController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Liste des types de reservation
        $types = TypeReservation::all();
        return view('admin.types_reservation.index', compact('types'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Formulaire de creation
        return view('admin.types_reservation.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request ->validate([
            'libelle' => 'required|in:passage,nuitée,sejour',
            'description' => 'nullable|string',
        ]);
          TypeReservation::create($request->all());

        return redirect()->route('admin.types_reservation.index')
                         ->with('success', 'Type de réservation créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //

        return redirect()->route('admin.types_reservation.index')
                         ->with('success', 'Type de réservation créé avec succès.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    // Validation
    $request->validate([
        'libelle' => 'required|in:passage,nuitée,sejour',
        'description' => 'nullable|string'
    ]);

    // Récupérer l'instance
    $typeReservation = TypeReservation::findOrFail($id);

    // Mise à jour
    $typeReservation->update($request->all());

    return redirect()->route('admin.types_reservation.index')
                     ->with('success', 'Type mis à jour.');
    }

    public function destroy(string $id){
    $typeReservation = TypeReservation::findOrFail($id);

    // Suppression
    $typeReservation->delete();

    return redirect()->route('admin.types_reservation.index')
                     ->with('success', 'Type supprimé.');
    }

}
