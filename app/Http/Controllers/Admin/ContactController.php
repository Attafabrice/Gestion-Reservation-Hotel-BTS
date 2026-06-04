<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends BaseController
{
    //Liste des conatcts
    public function index(){

        $contacts = Contact::latest()->paginate(10);
        $nonLus = Contact::where('lu', false)->count();
        return view('admin.contacts.index', compact('contacts', 'nonLus'));
    }

    //Detail d'un message
    public function show(Contact $contact){

        // Marquer comme lu
        $contact->update(['lu' => true]);
        return view('admin.contacts.show', compact('contact'));
    }

    //supression du message 
    public function destroy(Contact $contact){

        $contact->delete();
        return back()->with('success', 'Message supprimé.');
    }
}
