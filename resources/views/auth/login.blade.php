@extends('layouts.index')

@section('contenu')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 d-flex justify-content-center">
                <div class="wrapper-login mx-4">
                    <div class="p-4">
                        <p class="text-center text-uppercase">Se connecter</p>
                        <form method="POST" action="{{ route('userLogin') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="email">Adresse email: <span class="text-danger">*</span></label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" autocomplete="email" autofocus required>

                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="mot_de_passe">Mot de passe: <span class="text-danger">*</span></label>
                                <input id="mot_de_passe" type="password"
                                    class="form-control @error('mot_de_passe') is-invalid @enderror" name="mot_de_passe"
                                    autocomplete="mot_de_passe" required>
                                <i id="i-login"
                                    class="fa fa-eye-slash @error('mot_de_passe') hide @enderror text-secondary"></i>

                                @error('mot_de_passe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <a class="btn btn-link text-decoration-none" href="#">
                                    Mot de passe oublié?
                                </a>
                            </div>

                            <div class="form-group form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    Se souvenir de moi
                                </label>
                            </div>
                            <button class="form-control btn btn-primary" type="submit">Soumettre</button>
                            <div class="mt-2">
                                Pas encore un compte?<a class="text-decoration-none mx-2"
                                    href="{{ route('userRegister') }}">s'incrire
                                    maintenant</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
@endsection
