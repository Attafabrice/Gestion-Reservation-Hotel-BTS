@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <h1 class="mb-4">Connexion</h1>

        <div class="card">
            <div class="card-body">

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- REMEMBER --}}
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Se souvenir de moi
                        </label>
                    </div>

                    {{-- BUTTON --}}
                    <button type="submit" class="btn btn-primary w-100">
                        Se connecter
                    </button>
                </form>

                {{-- LINKS --}}
                {{-- <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('password.request') }}" class="small">Mot de passe oublié ?</a>
                    <a href="{{ route('register') }}" class="small">Créer un compte</a>
                </div> --}}

            </div>
        </div>

    </div>
</div>
@endsection