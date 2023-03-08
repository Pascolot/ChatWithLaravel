<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    <title> Discussion instantanée</title>
</head>

<body>

    <div class="contenu-principal d-flex flex-column">
        <nav class="navbar navbar-expand-sm bg-light d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-comments text-primary mx-2"></i>
                <span class="text-primary"> Discussion instantanée</span>
            </a>
            <div class="">
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link text-primary" href="{{ route('dashboard') }}">
                                Accueil
                                <i class="fas fa-home"></i>
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link text-primary" href="{{ route('userLogin') }}">
                                Se connecter
                                <i class="fas fa-sign-in"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-primary" href="{{ route('userRegister') }}">
                                S'enregistrer
                                <i class="fas fa-sign-in-alt"></i>
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </nav>

        @yield('contenu')

        <footer class="fixed-bottom mt-3 py-1 d-flex justify-content-center align-items-center bg-white">
            <p class="text-center mt-3">
                Copyright
                <i class="fas fa-copyright" aria-hidden="true"></i>
                2023 <a href="{{ url('/') }}" class="text-decoration-none">Discussion instantanée</a>
            </p>
        </footer>

    </div>

    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/pass-show-hide.js') }}" defer></script>

</body>

</html>
