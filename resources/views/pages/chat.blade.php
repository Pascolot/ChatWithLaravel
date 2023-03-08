@extends('layouts.app')

@section('contenu')
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-3 col-1"></div>
            <div class="col-md-6 col-10">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div>
                            <a href="{{ route('dashboard') }}" class="back-icon"><i class="fas fa-arrow-left"></i></a>
                            <img class="rounded-circle mx-3" width="46" height="46" src="{{ $user->image }}"
                                alt="" />
                        </div>
                        <div class="">
                            <span class="text-primary">{{ $user->nom }}</span>
                            <span class="text-primary">{{ $user->prenom }}</span> <br>
                            <span class="text-secondary">{{ $user->status }}</span>
                        </div>
                    </div>
                    <div id="chat-box" class="card-body">
                        {{-- ici se trouve les messages échangent entre les utilisateurs --}}
                    </div>
                    <div class="card-footer">
                        {{-- formulaire pour les messages --}}
                        <form action="#" class="p-4 typing-area">
                            @csrf
                            <input type="text" value="{{ Auth::user()->unique_id }}" name="messageEnvoye_id" hidden>
                            <input type="text" value="{{ $userUniqueId }}" name="messageRecu_id" hidden>
                            <input class="form-control" type="text" name="message"
                                placeholder="Ecrire un message ici..." />
                            <button class="btn btn-primary"><i class="fa fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-1"></div>
        </div>
    </div>
@endsection
