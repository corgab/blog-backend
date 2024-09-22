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

    <!-- Custom CSS -->    
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <h4 class="p-3">Dashboard</h4>
            <a href="{{ route('dashboard') }}">Home</a>
            <a href="{{ route('posts.index')}}">All Posts</a>
            <a href="{{ route('posts.create') }}">Create New Post</a>
            @if (Auth::user()->hasRole('admin') || $user->hasRole('editor'))
            <a href="{{ route('posts.drafts') }}">Draft Posts</a>
            @endif
            @if (Auth::user()->hasRole('admin'))
            <a href="{{ route('register')}}">Registra un Utente</a>
            @endif

            <!-- User Info -->
            <div class="user-info">
                @guest
                    <a href="{{ route('login') }}">Login</a>
                @else
                    <a href="{{ route('profile.edit') }}">Gestisci Profilo</a>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endguest
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
