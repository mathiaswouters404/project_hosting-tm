@extends("agenda.headers.header_template")

@section("firstname")
<span class="menu__profile__name">
    {{ $patient->firstName }}
</span>
@endsection

@section("create-event")
    <li class="agenda--button">
        <div id="create-event" onclick="NewEventService.newEvent()">Nieuw event</div>
    </li>
@endsection

@section("create-logs")
    <li class="agenda--button">
        <div id="create-log" onclick="LogService.newLog()">Nieuwe log</div>
    </li>
@endsection

@section("legend")
    <section class="menu__legend">
        <h2 class="text-white">Legende</h2>

        <ul>
            <li class="event_appointment legend--button" data-event-type-name="appointment">
                <i class="fa-solid fa-square-check"></i>
                <span>Afspraak</span>
            </li>
            <li class="event_task legend--button" data-event-type-name="task">
                <i class="fa-solid fa-square-check"></i>
                <span>Taak</span>
            </li>
            <li class="event_medication legend--button" data-event-type-name="medication">
                <i class="fa-solid fa-square-check"></i>
                <span>Medicatie</span>
            </li>
            <li class="event_questionnaire legend--button" data-event-type-name="questionnaire">
                <i class="fa-solid fa-square-check"></i>
                <span>Vragenlijst</span>
            </li>
        </ul>
    </section>
@endsection

@section("logout")
    <section class="menu__logout agenda--button">
        <a href="/patients">Naar overzicht</a>
    </section>
@endsection
