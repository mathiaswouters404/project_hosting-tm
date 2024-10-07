<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @yield('css_before')
    <title>@yield('title', 'PHP Project')</title>
    <title>Crud template</title>
</head>
<body class="d-flex flex-column min-vh-100">
<header>
    @include('shared.navigation')

    <h1 class="container mt-2 font-weight-bold">@yield('title', 'PHP Project')</h1>
</header>
<main class="container">
    @include('patients.modal_import')
    @include('patients.modal_rights')
    @include('questionnaires.questionnaire.create')

    @yield('main', 'Page under construction...')
</main>
@include('shared.footer')
<script src="{{ mix('js/app.js') }}"></script>
@yield('script_after')
</body>
</html>
