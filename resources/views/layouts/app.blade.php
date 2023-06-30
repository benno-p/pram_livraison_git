<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="shortcut icon" type="image/png" href="{{asset('images/favicon.ico')}}"/>

        <script type="application/javascript" src="{{ asset('js/app-v'.config('app.version').'.js') }}" defer></script>

        
        <link href="{{asset('css/all.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('css/styles-v'.config('app.version').'.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/argon-v'.config('app.version').'.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app-v'.config('app.version').'.css') }}" rel="stylesheet">


        <script type="text/javascript">
            var base_url = "{!! url('/') !!}";
        </script>
        <script src="{{ asset('js/moment.js') }}"></script>
    </head>

    <body class="g-sidenav-hidden">
        <div id="app" class="wrapper" data-user="{{isset($user) ? $user : ''}}" data-config="{{isset($config) ? $config : ''}}">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            @yield('content')
        </div>    
        <script type="application/javascript" src="{{ asset('js/global-v'.config('app.version').'.js') }}"></script>
    </body>
</html>
