<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    /**
     * Afficher la liste des rôles
     */
    public function index()
    {
        $roles = Role::latest()->paginate(10);

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Enregistrer un rôle
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:roles,nom',
            'description' => 'nullable|string'
        ]);

        Role::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'statut' => 'actif',
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rôle créé avec succès');
    }

    /**
     * Formulaire modification
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Mettre à jour un rôle
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:roles,nom,' . $role->id,
            'description' => 'nullable|string'
        ]);

        $role->update([
            'nom' => $request->nom,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rôle mis à jour');
    }
    //Modification du statut
    public function toggleStatus(Role $role){
        if ($role->nom === 'admin') {
            return back()->with('error','Impossible de modifier le rôle admin');
        }

        $role->statut = $role->statut === 'actif' ? 'inactif' : 'actif';
        $role->save();

        return back()->with('success','Statut du rôle modifié');
    }

      /**
     * Supprimer un rôle
     */
    public function destroy(Role $role)
    {
        if ($role->nom === 'admin') {
            return back()->with('error', 'Impossible de supprimer le rôle admin');
        }

        $role->delete();

        return back()->with('success', 'Rôle supprimé');
    }
}