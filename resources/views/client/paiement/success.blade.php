@extends('layouts.client')

@section('title', 'Paiement réussi')

@section('client-content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 text-center">

            <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
            </div>

            <h2 class="fw-bold mb-2">Paiement réussi !</h2>
            <p class="text-muted mb-4">
                Votre paiement a été traité avec succès. Vous recevrez une confirmation par email.
            </p>

            <a href="{{ route('client.reservation.index') }}" class="btn btn-primary">
                <i class="bi bi-calendar-check me-2"></i>Voir mes réservations
            </a>

        </div>
    </div>
</div>
@endsection