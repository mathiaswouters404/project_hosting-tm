<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <title>@yield('title', 'PHP Project')</title>
    <title>Crud template</title>
</head>
<body class="d-flex flex-column min-vh-100 front-page">
    <div class="row m-0 p-0">
        <div class="col-6 p-0">
            <img src="/img/fr-background-left.jpg" alt="" id="frontImage">
        </div>
        <div class="col-6 p-0">
            <div class="d-flex flex-column min-vh-100 align-items-center justify-content-center">
                @if (Route::has('login'))
                    @auth
                        <a class="text-white p-2" href="{{ url('/home') }}">Home</a>
                    @else
                        <a class="text-white p-2" href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a class="text-white p-2" href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                @endif</div>
        </div>
    </div>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>

