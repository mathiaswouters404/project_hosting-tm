@extends('layouts.template')

@section('title', 'Voorschriften')

@section('main')

    <div class="table-container mt-3 p-3">
        <h1 class="mb-3">{{ $patient->name ?? auth()->user()->firstName . ' ' . auth()->user()->lastName}}</h1>
        <p>
            <a href="#!" class="btn btn-outline-secondary font-weight-bold" id="btn-create">
                <i class="fas fa-plus-circle mr-1"></i>Nieuwe medicatie voorschijven
            </a>
        </p>
        <table class="table table-striped">
            <thead class="bg-dark text-white text-center">
            <tr>
                <th>Medicatie</th>
                <th>Dosis</th>
                <th>Reden</th>
                <th>Start</th>
                <th>Einde</th>
                <th>Voorgeschreven</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    @include('prescription.modal')
@endsection
@section('script_after')
    <script>

        PrescriptionService.init('{{ csrf_token() }}');
        @isset($patient)
            PrescriptionService.setPatient({{$patient->id}});
        @endisset

        $('#btn-create').click(function () {
            PrescriptionService.showModalPrescription("post", "Nieuw voorschrift voor {{$patient->name ?? ''}}", "/prescription");
        });

        $('tbody').on('click', '.btn-edit', function () {
            // Get data attributes from td tag
            const id = $(this).closest('td').data('id');
            const medication = $(this).closest('td').data('medication');
            const dosage = $(this).closest('td').data('dosage');
            const reason = $(this).closest('td').data('reason');
            const startDate = $(this).closest('td').data('startdate');
            const endDate = $(this).closest('td').data('enddate');

            PrescriptionService.showModalPrescription("put", "Wijzig voorschrift van {{ $patient->name ?? '' }}", `/prescription/${id}`, medication, dosage, reason, startDate, endDate);
        });

        $('tbody').on('click', '.btn-delete', function () {
            PrescriptionService.deleteFunction($(this));
        });

        // Submit the Bootstrap modal form with AJAX
        $('#modal-prescription form').submit(function (e) {
            // Don't submit the form
            e.preventDefault();
            PrescriptionService.submitFunction($('#modal-prescription form'));
        });

    </script>
@endsection
