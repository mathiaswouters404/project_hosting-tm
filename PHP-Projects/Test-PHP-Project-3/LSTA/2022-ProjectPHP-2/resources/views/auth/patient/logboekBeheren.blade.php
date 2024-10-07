@extends('layouts.template')

@section('title', 'Logboek beheren')

@section('main')
    <section class="navigation">
        <div  class="navigation__top row justify-content-between mx-2">
            <h1 class="navigation__top--left">helloz?</h1>
            <div class="navigation__top--right">
                <a href="/" ><i class="fa-solid fa-rotate-left fa-2x"></i></a>
                <a href="/" ><i class="fa-solid fa-house fa-2x"></i></a>
            </div>
        </div>
        <div class="navigation__bottom row justify-content-between mx-2">
            <div>&nbsp;</div>
            <div class="navigation__bottom--center pagination">
                <button class="pagination__button"><i class="fa-solid fa-caret-left"></i></button>
                <button class="pagination__button">1</button>
                <button class="pagination__button">2</button>
                <button class="pagination__button">3</button>
                <button class="pagination__button"><i class="fa-solid fa-caret-right"></i></button>
            </div>
            <a href="#" class="n">
                <i class="fa-solid fa-book-medical"></i>
                Log toevoegen
            </a>
        </div>
    </section>

    <section class="table-responsive Logs">
        <table class="table logs__table">
            <thead class="logs__table__heading">
                <tr class="logs__table__row">
{{--                    7 fields in the table --}}
                    <th class="logs__table__number">#</th>
                    <th class="logs__table__title">Title</th>
                    <th class="logs__table__preview">Preview</th>
                    <th class="logs__table__bezoeker">Bezoeker</th>
                    <th class="logs__table__datum">Datum</th>
                    <th class="logs__table__afspraak">Afspraak</th>
                    <th class="logs__table__crud"></th>
                </tr>
            </thead>
            <tbody>
                <tr class="logs__table__row">
                    <td class="logs__table__number">1</td>
                    <td class="logs__table__title">log1</td>
                    <td class="logs__table__preview">some Dummy text</td>
                    <td class="logs__table__bezoeker">Tim</td>
                    <td class="logs__table__datum">19/10/1997</td>
                    <td class="logs__table__afspraak">Birthday</td>
                    <td class="logs__table__crud">
                        <div>
                            <i class="fas fa-edit"></i>
                            <i class="fas fa-trash-alt"></i>
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
@endsection

@section('script_after')
    <script>
        // Load the logs table
        fillTable();
        // Load the logs with AJAX.
        function fillTable(){
            $.getJSON('/patient/logs/queryLogs')
                .done(function (data) {
                    // Clear tbody tag so no data is shown.
                    $('tbody').empty();
                    // Loop over each item in the array.
                    $.each(data, function (key, value) {
                        let tr = `<tr>
                            <td>${value.id}</td>
                            <td>${value.title}</td>
                            <td>${value.description}</td>
                        </tr>`;
                        // Append the row into the table and re-loop.
                        $('tbody').append(tr);
                    });
                })
            }
    </script>
@endsection
