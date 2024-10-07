@extends('layouts.template')

@section('title', 'Logboek beheren')

@section('main')
<section class="title">
            <H1 class="title__nameField mt-4 mb-4 p-3 page-title text-secondary flex-grow-1 mr-3">
                {{ $user->lastName }} {{ $user->firstName }} overzicht
            </H1>
</section>

<section class="table-container p-3 Logs">
    <div class="d-flex">
        <form class="flex-grow-1 mr-3" id="searchFrom">
            <input type="text" class="form-control" name="searchLogs" id="searchLogs"
                   value="{{ request()->searchLogs }}" placeholder="Search logs">
        </form>
        <button class="btn btn-dark mb-3 search-log" id="create-log" role="button"><i class="fa-solid fa-book-medical mr-1"></i>Nieuwe log</button>
    </div>
    <!-- Table overflows on small screen without 'table-responsive' -->
    <table class="table table-striped m-0 table-hover table-responsive logs__table text-center">
        <thead class="logs__table__heading bg-dark text-white row no-gutters">
        <tr class="logs__table__row col-12 row no-gutters">
            {{--                    6 fields in the table --}}
            <th scope="col" class="logs__table__patient col-4 col-lg-2">Eigenaar</th>
            <th scope="col" class="logs__table__title col-4 col-lg-2">Titel</th>
            <th scope="col" class="logs__table__preview col-4 col-lg-2">Preview</th>
            <th scope="col" class="logs__table__datum col-4 col-lg-2">Datum</th>
            <th scope="col" class="logs__table__afspraak col-4 col-lg-2">Afspraak</th>
            <th scope="col" class="logs__table__crud col-4 col-lg-2"></th>
        </tr>
        </thead>
        <tbody class="logs__table__body row no-gutters">
        <tr class="logs__table__row col-12 row no-gutters">
            <td class="logs__table__patient col-4 col-lg-2">Error</td>
            <td class="logs__table__title col-4 col-lg-2">Herlaad de pagina.</td>
            <td class="logs__table__preview col-4 col-lg-2">De logs zijn niet geladen!</td>
            <td class="logs__table__datum col-4 col-lg-2">--/--/--</td>
            <td class="logs__table__afspraak col-4 col-lg-2"></td>
            <td class="logs__table__crud col-4 col-lg-2">
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
@include('log.editModal')
{{--@include('log.createModal')--}}
@endsection

@section('script_after')
<script>
    AddLog.loadTable();

    // Popup for the edit button.
    $('tbody').on('click', '.edit-log', function () {
        AddLog.clearModal();
        AddLog.editLog(this);
    });

    // Popup for the create-new button.
    $('#create-log').on('click', function () {
        AddLog.clearModal();
        AddLog.createLog();
    });

    // Popup the info button.
    $('tbody').on('click', '.info-log', function () {
        AddLog.clearModal();
        AddLog.infoLog(this);
    });

    // Popup the delete button
    $('tbody').on('click', '.delete-log', function () {
        AddLog.deleteLog(this, '{{ csrf_token() }}')
    });

    // Submit the Bootstrap modal form with AJAX
    $('#modal-log form').submit(function (e) {
        // Don't submit the form
        e.preventDefault();
        AddLog.submit(this);
    });

</script>
@endsection

{{--<td>--}}
    {{--    ${ value.visitor ? value.visitor : '<i class="far fa-times-circle text-danger"></i>' }--}}
    {{--</td>--}}
