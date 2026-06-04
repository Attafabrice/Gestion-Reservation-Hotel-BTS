<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create(){
        return view('auth.register');
    }

    public function store(Request $request){
        $data = $request->validate([
            'nom'       => ['required', 'string', 'max:255'],
            'prenoms'   => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'telephone' => ['required', 'string', 'max:20'],
            'password'  => ['required', 'min:6', 'confirmed'],
        ]);

        // si le rôle client n'existe pas, on bloque
        $roleClient = Role::where('nom', 'client')->first();

        if (!$roleClient) {
            return back()->with('error', 'Erreur de configuration : rôle client introuvable.');
        }

        $user = User::create([
            'nom'       => $data['nom'],
            'prenoms'   => $data['prenoms'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'telephone' => $data['telephone'],
            'role_id'   => $roleClient->id,
            'statut'    => 'actif',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('client.accueil')->with('success', 'Compte créé avec succès ! Bienvenue.');
    }
}