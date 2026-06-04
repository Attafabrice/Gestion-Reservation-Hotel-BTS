@extends('layouts.admin')

@section('admin-content')

<div class="container mt-4">

    <h2>Détail du message</h2>

    <div class="card p-4">

        <h4>{{ $contact->nom }}</h4>
        <p><strong>Email :</strong> {{ $contact->email }}</p>
        <p><strong>Sujet :</strong> {{ $contact->sujet }}</p>

        <hr>

        <p>{{ $contact->message }}</p>

    </div>

</div>

@endsection