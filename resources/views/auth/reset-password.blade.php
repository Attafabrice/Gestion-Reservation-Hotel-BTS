@extends('layouts.auth')

@section('title', 'Réinitialiser mot de passe')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <h1 class="mb-4">Réinitialiser le mot de passe</h1>

        <div class="card">
            <div class="card-body">

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email"
                            value="{{ old('email', $email) }}"
                            class="form-control @error('email') is-invalid @enderror"
                            required>

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Nouveau mot de passe</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            required>

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation"
                            class="form-control" required>
                    </div>

                    <button class="btn btn-success w-100">
                        Réinitialiser
                    </button>
                </form>

                <div class="mt-3 text-center">
                    <a href="{{ route('login') }}">Retour connexion</a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection