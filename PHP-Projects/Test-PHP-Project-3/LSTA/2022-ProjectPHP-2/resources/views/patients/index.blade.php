@extends('layouts.template')

@section('title', 'Patiënten beheren')

@section('main')

    <div id="manage_patients">
        <div class="text-right">
            <a id="import_patient" href="#"><i class="fa-solid fa-plus"></i> Patiënt toevoegen</a>
        </div>
        <table class="table table-hover mt-2" id="manage-patients__table">
        </table>
    </div>

@endsection

@section('script_after')
    <script>
        window.Patients.init("{{ csrf_token() }}", {{Auth::user()->role_id}});
    </script>
@endsection
