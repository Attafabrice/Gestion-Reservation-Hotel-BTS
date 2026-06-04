<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use App\Models\TypeChambre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChambreController extends BaseController
{
    // Liste des chambres
    public function index(){
        $chambres = Chambre::with('type')->latest()->paginate(10); 
        return view('admin.chambres.index', compact('chambres'));
    }

    // Formulaire création
    public function create(){
        $types = TypeChambre::all();
        return view('admin.chambres.create', compact('types'));
    }

    // Sauvegarder nouvelle chambre
    public function store(Request $request){
        $data = $request->validate([
            'numero' => 'required|unique:chambres,numero',
            'etage' => 'nullable|integer',
            'description' => 'nullable|string',
            'capacite' => 'nullable|integer',
            'surface' => 'nullable|integer',
            'image' => 'required|image|mimes:jpg,webp,jfif,jpeg,png|max:2048',
            'type_chambre_id' => 'required|exists:type_chambres,id',
            'statut' => 'required|in:libre,occupee,maintenance',
        ]);

        // Upload de l'image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('chambres', 'public');
        }

        Chambre::create($data);

        return redirect()->route('admin.chambres.index')
            ->with('success', 'Chambre ajoutée avec succès.');
    }

    // Formulaire édition
    public function edit(Chambre $chambre)
    {
        $types = TypeChambre::all();
        return view('admin.chambres.edit', compact('chambre', 'types'));
    }

    // Mise à jour
    public function update(Request $request, Chambre $chambre)
    {
        $data = $request->validate([
            'numero' => 'required|unique:chambres,numero,' . $chambre->id,
            'etage' => 'nullable|integer',
            'description' => 'nullable|string',
            'capacite' => 'nullable|integer',
            'surface' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,webp,jfif,jpeg,png|max:2048',
            'type_chambre_id' => 'required|exists:type_chambres,id',
            'statut' => 'required|in:libre,occupee,maintenance',
        ]);

        // Si nouvelle image, supprimer l'ancienne et uploader la nouvelle
        if ($request->hasFile('image')) {
            if ($chambre->image && Storage::disk('public')->exists($chambre->image)) {
                Storage::disk('public')->delete($chambre->image);
            }
            $data['image'] = $request->file('image')->store('chambres', 'public');
        }

        $chambre->update($data);

        return redirect()->route('admin.chambres.index')
            ->with('success', 'Chambre mise à jour avec succès.');
    }

    // Supprimer chambre
    public function destroy(Chambre $chambre)
    {
        // Supprimer l'image si elle existe
        if ($chambre->image && Storage::disk('public')->exists($chambre->image)) {
            Storage::disk('public')->delete($chambre->image);
        }

        $chambre->delete();

        return redirect()->route('admin.chambres.index')
            ->with('success', 'Chambre supprimée avec succès !');
    }
}