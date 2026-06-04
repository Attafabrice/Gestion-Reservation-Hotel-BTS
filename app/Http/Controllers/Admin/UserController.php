<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{ 
    //  Liste des utilisateurs exclure les admins de la liste clients
    public function index(){
        $users = User::whereHas('role', fn($q) => $q->where('nom', '!=', 'admin'))
                    ->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }
        // admins() — liste uniquement les admins
    public function admins(){
        $admins = User::whereHas('role', fn($q) => $q->where('nom', 'admin'))
                    ->latest()->paginate(10);
        return view('admin.users.admins', compact('admins'));
    }

    //Formulaire de creation
    public function create(){
        $roles = Role::all();
        return view('admin.users.create',compact('roles'));
    }

    //Enregistrement d'un nouvel user
    public function store(Request $request){
        $request->validate([
            'nom' => ['required','string','max:20'],
            'prenoms' => ['required','string','max:25'],
            'email' => ['required','email','unique:users,email'],
            'telephone' => ['required','string','max:10'],
            'role_id' => ['required','exists:roles,id'],
            'password' => ['required','string','min:6','confirmed'],
        ]);

        User::create([
            'nom'  => $request->nom,
            'prenoms'  => $request->prenoms,
            'email'  => $request->email,
            'telephone'  =>$request->telephone,
            'password'  => Hash::make(($request->password)),
            'role_id'  => $request->role_id,
            'statut'  => 'actif',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès !');
    }

    //Formulaire d'edition
    public function edit(User $user){
        $roles = Role::all();
        return view('admin.users.edit', compact('user','roles'));

    }

    //Mis a jour
    public function update(Request $request, User $user){
            $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'required|string|max:20',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update([
            'nom' => $request->nom,
            'prenoms' => $request->prenoms,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'role_id' => $request->role_id,
        ]);
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès !');
    }

    //Activer ou descativer un statut 
    public function toggleStatus(User $user){
        $user -> statut = $user->statut === 'actif' ? 'inactif' : 'actif';
        $user->save();

         return redirect()->route('admin.users.index')->with('success', 'Statut de l’utilisateur mis à jour !');
    }
    
     // Suppression d'un utilisateur
    public function destroy(User $user){
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
}
