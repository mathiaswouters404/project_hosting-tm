@extends('layouts.template')
@section('title', '')
@section('main')
    <h1 class="text-center my-5">Error 404</h1>
    <h2 class="text-center text-black-50">{{ $exception->getMessage() ?: 'Pagina niet gevonden' }}</h2>
    @include('errors.buttons ')
@endsection

@section('script_after')
    <script>
        // Go back to the previous page
        $('#back').click(function () {
            window.history.back();
        });

        // Remove the right navigation
        $('.nav-item').hide();
    </script>
@endsection
