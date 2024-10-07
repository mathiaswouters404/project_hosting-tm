@extends('layouts.template')

@section('title', 'Medicatie')

@section('main')
    <div class="table-container my-3 p-3">
        <form action="" id="filterForm">
            <div class="row">
                <div class="form-group col-sm-7 mb-3">
                    <label for="filterName">Filter op naam</label>
                    <input type="text" class="form-control" name="name" id="filterName" placeholder="Filter op naam">
                </div>
                <div class="form-group col-sm-5 mb-3">
                    <label for="sort">Sorteren</label>
                    <select name="sort" id="sort" class="form-control">
                        @for($i=0; $i < count($sorts); $i++)
                            <option value="{{$i}}">{{$sorts[$i][0]}}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </form>
        <p>
            <a href="#!" class="btn btn-outline-secondary font-weight-bold" id="btn-create">
                <i class="fas fa-plus-circle mr-1"></i>Nieuwe Medicatie
            </a>
        </p>
        <table class="table table-striped">
            <thead class="bg-dark text-white">
            <tr>
                <th class="text-center">#</th>
                <th>Naam</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    @include('medication.modal')
    @include('medication.modal_prescription')
@endsection

@section('script_after')
    <script>
        MedicationService.init('{{csrf_token()}}')

        $('#filterForm').submit(function(e) {
            e.preventDefault();
            updateFilter();

        })
        $('#filterName').blur(updateFilter);
        $('#sort').change(updateFilter);

        function updateFilter() {
            MedicationService.setFilter($('#filterForm').serialize());
        }
        // Popup a dialog
        $('tbody').on('click', '.btn-delete', function () {
            MedicationService.deleteFunction($(this));
        });

        $('tbody').on('click', '.btn-edit', function () {
            // Get data attributes from td tag
            const id = $(this).closest('td').data('id');
            const name = $(this).closest('td').data('name');
            const description = $(this).closest('td').data('description');
            MedicationService.showModal("put", "Medicatie wijzigen", `/medication/${id}`, name, description);
        });

        $('tbody').on('click', '.btn-prescribe', function() {
            MedicationService.prescribeFunction($(this));
        });

        $('#btn-create').click(function () {
            MedicationService.showModal("post", "New medication", "/medication");
        });


        // Submit the Bootstrap modal form with AJAX
        $('#modal-medication form').submit(function (e) {
            // Don't submit the form
            e.preventDefault();
            MedicationService.submitFunction($(this));
        });

        $('#modal-prescription form').submit(function (e) {
            e.preventDefault();
            MedicationService.submitPrescription($(this));
        });

    </script>
@endsection

