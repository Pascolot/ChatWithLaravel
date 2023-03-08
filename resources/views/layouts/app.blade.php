<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    <title>Discussion instantanée</title>
</head>

<body>

    <nav class="navbar bg-light navbar-expand-sm d-flex justify-content-between">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-comments text-primary mx-2"></i>
            <span class="text-primary">Discussion instantanée</span>
        </a>

        <div class="">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-primary" href="#">Utilisateur <span
                            class="test">{{ Auth::user()->unique_id }}</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-primary" href="{{ route('logout', Auth::user()->unique_id) }}">Deconnexion
                        <i class="fa fa-power-off"></i> </a>
                </li>
            </ul>
        </div>
    </nav>

    {{-- contenu --}}
    @yield('contenu')

    <footer class="fixed-bottom mt-3 py-1 d-flex justify-content-center align-items-center bg-white">
        <p class="text-center mt-3">
            Copyright
            <i class="fas fa-copyright" aria-hidden="true"></i>
            2023 <a href="{{ url('/') }}" class="text-decoration-none">Discussion instantanée</a>
        </p>
    </footer>

    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/user.js') }}"></script>
    <script src="{{ asset('js/chat.js') }}"></script>
</body>

</html>
