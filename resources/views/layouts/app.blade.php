<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token para proteger las solicitudes POST -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gestion de Usuarios') }}</title>


    <!-- Enlace a Bootstrap CSS para la estilización de la página -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<style>
    /* Fondo azul para toda la página */
    body {
        background-color: #85b1ae; /* Azul */
        color: rgb(32, 29, 29); /* Texto blanco para que contraste bien con el fondo azul */
    }


    .navbar a {
        color: white; /* Color de los enlaces de la navbar */
    }

    .dropdown-menu {
        background-color: #5f9090; /* Fondo oscuro para el menú desplegable */
    }

</style>
<body>
    <div id="app">
        <!-- Barra de navegación con el diseño de Bootstrap -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <!-- Nombre de la aplicación, redirige a la página principal -->
                <a class="navbar-brand" href="{{ url('/') }}">User Managment App
                </a>

                <!-- Botón para móviles que permite desplegar el menú de navegación -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">


                    <!-- Lado derecho del menú de navegación -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Enlaces de autenticación -->
                        @guest
                            <!-- Si el usuario no está autenticado, mostrar los enlaces de Login y Register -->
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <!-- Si el usuario está autenticado, mostrar el nombre del usuario y un menú desplegable -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <!-- Nombre del usuario autenticado -->
                                </a>

                                <!-- Menú desplegable con las opciones disponibles para el usuario -->
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <!-- Enlace a la página de perfil del usuario -->
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        {{ __('Perfil') }}
                                    </a>

                                    <!-- Enlace al Dashboard, donde aparecera el listado de usuarios con su funcionalidad -->
                                    <a class="dropdown-item" href="{{ route('users.index') }}">
                                        {{ __('Dashboard') }}
                                    </a>

                                    <!-- Enlace para cerrar sesión -->
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                 document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <!-- Mostrar enlace de Gestión de Usuarios solo si el usuario tiene rol 'admin' -->
                                    @auth
                                        @if(Auth::user()->role === 'admin')
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('users.index') }}">{{ __('User List') }}</a>
                                            </li>
                                        @endif
                                    @endauth

                                    <!-- Formulario de cierre de sesión -->
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf 
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>
</body>

</html>
