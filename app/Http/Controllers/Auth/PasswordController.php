<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function edit(){
        return view('auth.password');
    }

    public function update(Request $request){
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'min:6', 'confirmed'],
        ]);

        $user = $request->user();
        $user->password = Hash::make($data['password']);
        $user->save();

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }
}