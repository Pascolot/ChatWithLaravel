@extends('layouts.index')

@section('contenu')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 d-flex justify-content-center">
                <div class="wrapper-login mx-4">
                    <div class="p-4">
                        <p class="text-center text-uppercase">Se connecter</p>
                        <form method="POST" action="{{ route('auth.connection') }}">
                            @csrf

                            @if (Session::get('erreur'))
                                <div class="alert alert-danger">{{ Session::get('erreur') }}</div>
                            @endif

                            <div class="form-group mb-3">
                                <label for="email">Adresse email: <span class="text-danger">*</span></label>
                                <input id="email" type="email" class="form-control" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>

                            <div class="form-group mb-3">
                                <label for="mdp">Mot de passe: <span class="text-danger">*</span></label>
                                <input id="mdp" type="password" class="form-control" name="mdp" required
                                    autocomplete="current-password">
                                <i id="i-login" class="fa fa-eye-slash text-secondary"></i>

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
                                    href="{{ route('auth.register') }}">s'incrire
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
