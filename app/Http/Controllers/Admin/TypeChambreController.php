<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TypeChambre;

class TypeChambreController extends BaseController
{
    // Afficher tous les types
    public function index()
    {
        $types = TypeChambre::all();
        return view('admin.type_chambres.index', compact('types'));
    }

    // Formulaire création
    public function create()
    {
        return view('admin.type_chambres.create');
    }

    // Enregistrer nouveau type
    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'equipements' => 'required|string',
        ]);

        // Transformer la chaîne en tableau
        if (!empty($data['equipements'])) {
            $data['equipements'] = array_map('trim', explode(',', $data['equipements']));
        }

        TypeChambre::create($data);

        return redirect()->route('admin.type_chambres.index')->with('success', 'Type de chambre créé avec succès.');
    }


    // Formulaire édition
    public function edit(TypeChambre $typeChambre)
    {
        return view('admin.type_chambres.edit', compact('typeChambre'));
    }

    // Mettre à jour le type
    public function update(Request $request, TypeChambre $typeChambre)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'equipements' => 'required|string', 
        ]);

        // Transformer la chaîne en tableau
        $data['equipements'] = array_map('trim', explode(',', $data['equipements']));

        $typeChambre->update($data);

        return redirect()->route('admin.type_chambres.index')->with('success', 'Type de chambre mis à jour.');
    }


    // Supprimer un type
    public function destroy(TypeChambre $typeChambre)
    {
        $typeChambre->delete();
        return redirect()->route('admin.type_chambres.index')
                         ->with('success', 'Type de chambre supprimé.');
    }
}
