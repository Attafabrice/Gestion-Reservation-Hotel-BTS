<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TypeReservation;
use App\Models\TypeChambre;
use App\Models\Tarif;
use Illuminate\Http\Request;

class TarifController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        // Liste des tarifs
        // $tarifs = Tarif::get();
        // return view('admin.tarifs.index', compact('tarifs'));
        $tarifs = Tarif::with(['typeChambre', 'typeReservation'])->paginate(10);
        return view('admin.tarifs.index', compact('tarifs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){
        $chambres = TypeChambre::all();
        $typesReservation = TypeReservation::all();

        return view('admin.tarifs.create', compact('chambres', 'typesReservation'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'type_chambre_id' => 'required|exists:type_chambres,id',
            'type_reservation_id' => 'required|exists:type_reservations,id',
            'prix' => 'required|numeric|min:0',
        ]);
        Tarif::create($data);
        return redirect()->route('admin.tarifs.index')
                         ->with('success', 'Tarif ajouté avec succès.');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tarif $tarif){

        $chambres = TypeChambre::all();
        $typesReservation = TypeReservation::all();

        return view('admin.tarifs.edit', compact('tarif','chambres','typesReservation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarif $tarif)
    {
        $data = $request->validate([
            'type_chambre_id' => 'required|exists:type_chambres,id',
            'type_reservation_id' => 'required|exists:type_reservations,id',
            'prix' => 'required|numeric|min:0',
        ]);
        $tarif->update($data);
        return redirect()->route('admin.tarifs.index')
                         ->with('success', 'Le tarif a été à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarif $tarif)
    {
        //suppression d'un tarif
        $tarif->delete();
        return redirect()->route('admin.tarifs.index')->with('success', 'Chambre supprimée.');
    }
}
