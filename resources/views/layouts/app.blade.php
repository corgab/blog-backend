<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Usando Vite -->
    @vite(['resources/js/app.js'])
    @yield('head')

    <!-- Custom CSS -->
    @stack('styles')
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <h4 class="p-3">Dashboard</h4>
            <a href="{{ route('dashboard') }}">Home</a>
            @if (Auth::user()->hasRole('user'))
                <!-- Logica utente -->
            @else
                <a href="{{ route('posts.index') }}">Posts</a>
                <a href="{{ route('posts.create') }}">Crea nuovo post</a>
                <a href="{{ route('posts.trash') }}">Cestino</a>

                @if (Auth::user()->hasRole('admin'))
                    <a href="{{ route('posts.approve') }}">Approva Posts</a>
                    <a href="{{ route('register') }}">Registra un Utente</a>
                    <a href="{{ route('tags.index') }}">Tags</a>
                @endif
            @endif

            <!-- User Info -->
            <div class="user-info">
                {{-- <a href="{{ route('lang.switch', app()->getLocale() == 'en' ? 'it' : 'en') }}" class="fs-6">
                    {{ __('Change language') }}
                </a> --}}
                <a href="{{ route('profile.edit') }}">Gestisci Profilo</a>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>
</body>
@yield('scripts')

</html>
