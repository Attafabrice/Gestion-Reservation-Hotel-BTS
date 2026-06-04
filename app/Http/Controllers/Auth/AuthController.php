<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function create(Request $request){
        if ($request->has('redirect')) {
            $redirectUrl = $request->redirect;
            if (str_starts_with($redirectUrl, url('/'))) {
                session()->put('url.intended', $redirectUrl);
            }
        }
        return view('auth.login');
    }

       public function store(Request $request){
        // dd('store() appelé', $request->all(), Auth::check());
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt([
            'email'    => $credentials['email'],
            'password' => $credentials['password'],
            'statut'   => 'actif',
        ], $remember)) {

            //Sauvegarder avant regenerate()
            $pending  = $request->session()->get('reservation_pending');
            $intended = $request->session()->get('url.intended');

            $request->session()->regenerate(); // ← c'est lui qui effaçait tout

            //Restaurer après regenerate()
            if ($pending) {
                $request->session()->put('reservation_pending', $pending);
            }
            if ($intended) {
                $request->session()->put('url.intended', $intended);
            }

            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('client.accueil'));
        }
        return back()->withErrors(['email' => 'Identifiants invalides ou compte inactif.'])
                ->withInput($request->only('email'));
    }

    public function destroy(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}