<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordLinkController extends Controller
{
    public function create(){
        return view('auth.forgot-password');
    }

    public function store(Request $request){
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Lien de réinitialisation envoyé par email.');
        }

        return back()->withErrors([
            'email' => 'Email introuvable ou erreur d\'envoi.',
        ])->onlyInput('email');
    }
}