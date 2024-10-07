@extends("agenda.headers.header_template")

@section("firstname")
    <span class="menu__profile__name">
    {{ $patient->firstName }}
    </span>
@endsection

@section("logout")
    <section class="menu__logout agenda--button">
        <a href="/patients">Naar overzicht</a>
    </section>
@endsection
