<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    //
    public function index(){

        return view('client.contact');
    }

    public function store(Request $request){
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        Contact::create($request->all());
        return back()->with('success', 'Message envoyé avec succès !');
    }
}