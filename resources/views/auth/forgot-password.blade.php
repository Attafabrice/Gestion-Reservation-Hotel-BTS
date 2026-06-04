@extends('layouts.auth')

@section('title', 'Mot de passe oublié')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <h1 class="mb-4">Mot de passe oublié</h1>

        <div class="card">
            <div class="card-body">

                <p class="text-muted">
                    Entre ton email pour recevoir un lien de réinitialisation.
                </p>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required>

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-primary w-100">
                        Envoyer le lien
                    </button>
                </form>

                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('login') }}">Connexion</a>
                    <a href="{{ route('register') }}">Créer un compte</a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection