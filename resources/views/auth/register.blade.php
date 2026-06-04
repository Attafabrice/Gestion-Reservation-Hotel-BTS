@extends('layouts.auth')

@section('title', 'Inscription')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <h1 class="mb-4">Inscription</h1>

        <div class="card">
            <div class="card-body">

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- NOM --}}
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input
                            id="nom"
                            type="text"
                            name="nom"
                            class="form-control @error('nom') is-invalid @enderror"
                            value="{{ old('nom') }}"
                            required
                        >
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PRENOMS --}}
                    <div class="mb-3">
                        <label for="prenoms" class="form-label">Prénoms</label>
                        <input
                            id="prenoms"
                            type="text"
                            name="prenoms"
                            class="form-control @error('prenoms') is-invalid @enderror"
                            value="{{ old('prenoms') }}"
                            required
                        >
                        @error('prenoms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TELEPHONE --}}
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input
                            id="telephone"
                            type="text"
                            name="telephone"
                            class="form-control @error('telephone') is-invalid @enderror"
                            value="{{ old('telephone') }}"
                            required
                        >
                        @error('telephone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                required
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmer</label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                required
                            >
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- SUBMIT --}}
                    <button type="submit" class="btn btn-primary w-100">
                        Créer un compte
                    </button>
                </form>

                {{-- LOGIN LINK --}}
                {{-- <p class="text-muted small mt-3 mb-0">
                    Déjà inscrit ? <a href="{{ route('login') }}">Connexion</a>
                </p> --}}

            </div>
        </div>

    </div>
</div>
@endsection