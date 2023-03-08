@extends('layouts.app')

@section('contenu')
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-3 col-sm-1"></div>
            <div class="col-md-6 col-sm-10">
                <div class="card">
                    <div class="card-header d-flex">

                        <img class="rounded-circle mx-2" width="46" height="46" src="{{ Auth::user()->image }}"
                            alt="" />
                        <div class="details">
                            <span>{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</span>
                            <p>{{ Auth::user()->status }}</p>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-8">
                            <p class="mt-4">Rechercher un utilisateur pour parler</p>
                            <form class="form-search" action="">
                                @csrf

                                <input id="cherche" class="form-control" name="cherche" type="text"
                                    placeholder="Entrer le nom à rechercher... " />
                            </form>
                        </div>
                        <div class="col-2"></div>
                    </div>
                    <div class="card-body">
                        <h4 class="text-center">Autres utilisateurs</h4>
                        <div class="users-list">
                            {{-- ici se trouve les messages envoyés et reçus par un utilisateur --}}
                        </div>
                    </div>
                </div>
                <div class="col col-md-3 col-sm-1"></div>
            </div>
        </div>
    </div>
@endsection
