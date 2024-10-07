<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <title>Agenda</title>
</head>

<body data-id="{{ $patient->id }}">

@if (Auth::user()->role->name == "Patient")
    @include("agenda.headers.header_patient")

@elseif (Auth::user()->role->name == "Mantelzorger")
    @include("agenda.headers.header_caretaker")
@else
    @include("agenda.headers.header_doctor")
@endif

@include("questionnaires.answer.create")
@include('agenda.modals.event_modal')
@include('agenda.modals.log_modal')
@include('agenda.modals.user_guide_modal')

<main class="agenda">
    <section class="agenda__date">
        <div class="agenda__date--previous">
            <i class="fa-solid fa-chevron-left"></i>
        </div>
        <div class="agenda__date--date">
        </div>
        <div class="agenda__date--next">
            <i class="fa-solid fa-chevron-right"></i>
        </div>
    </section>

    <div class="agenda__days">
        <div class="agenda__days--filler"></div>

        <section class="agenda__days--content">
            <ul>
            </ul>
        </section>
    </div>

    <section class="agenda__events">
        <div class="agenda__events--hours">
            <ul>
                <li>
                    <div>01u</div>
                </li>
                <li>
                    <div>02u</div>
                </li>
                <li>
                    <div>03u</div>
                </li>
                <li>
                    <div>04u</div>
                </li>
                <li>
                    <div>05u</div>
                </li>
                <li id="agenda__events--hours--6">
                    <div>06u</div>
                </li>
                <li>
                    <div>07u</div>
                </li>
                <li>
                    <div>08u</div>
                </li>
                <li>
                    <div>09u</div>
                </li>
                <li>
                    <div>10u</div>
                </li>
                <li>
                    <div>11u</div>
                </li>
                <li>
                    <div>12u</div>
                </li>
                <li>
                    <div>13u</div>
                </li>
                <li>
                    <div>14u</div>
                </li>
                <li>
                    <div>15u</div>
                </li>
                <li>
                    <div>16u</div>
                </li>
                <li>
                    <div>17u</div>
                </li>
                <li>
                    <div>18u</div>
                </li>
                <li>
                    <div>19u</div>
                </li>
                <li>
                    <div>20u</div>
                </li>
                <li>
                    <div>21u</div>
                </li>
                <li>
                    <div>22u</div>
                </li>
                <li>
                    <div>23u</div>
                </li>
            </ul>
        </div>

        <div class="agenda__events--e">
            <div class="agenda__events--separators">
            </div>

            <div class="agenda__events--events">
                <!-- give top: (uur / 24 * 100) -->
            </div>
        </div>
    </section>
</main>

<script src="{{ mix('js/app.js') }}"></script>
<script>
    window.AgendaService.init("{{ csrf_token() }}", {{ $patient->id }}, "{{Auth::user()->role->name}}");
    window.EventService.init("{{ csrf_token() }}", {{ $patient->id }});
    AnswerService.setToken("{{csrf_token()}}");
    ProgressService.setToken("{{csrf_token()}}");
    AnswerService.init();
</script>

</body>

</html>
