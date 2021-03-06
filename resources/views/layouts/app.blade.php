<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Spalear') }}</title>
        <!-- Favicon -->
        <link href="{{ asset('argon') }}/img/brand/favicon.png" rel="icon" type="image/png">
        <!-- CSS -->
        <link type="text/css" href="{{ asset('css') }}/app.css" rel="stylesheet">        
    </head>
    <body class="{{ $class ?? '' }}" >
        <div id="app">            
            @auth()
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                @include('layouts.navbars.sidebar')
            @endauth
            
            <div class="main-content" >
                @include('layouts.navbars.navbar')                  
                @yield('content')
            </div>

            @guest()
                @include('layouts.footers.guest')
            @endguest
        </div>
        
        @stack('js')
        
        <!-- JS -->
        <script src="{{ asset('js') }}/app.js"></script>
    </body>
</html>