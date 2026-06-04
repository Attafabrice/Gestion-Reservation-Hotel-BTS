@extends('layouts.auth')

@section('title', 'Changer le mot de passe')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <h1 class="mb-4">Changer le mot de passe</h1>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('password.change') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input
                                id="current_password"
                                name="current_password"
                                type="password"
                                class="form-control @error('current_password') is-invalid @enderror"
                                required
                            >
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
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
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmer</label>
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    class="form-control"
                                    required
                                >
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-4">
                            Mettre à jour
                        </button>
                    </form>

                    <div class="mt-3">
                        <a href="{{ route('client.accueil') }}" class="small">Retour à la page d'accueil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

