@extends('layouts.template')
@include('questionnaires.questionnaire.overview')
@section('main')
    <div>
        <h1>Vragenlijsten</h1>

        <section class="table-container p-3">
        <table class="table table-striped m-0 table-hover table-responsive">
            <thead class="bg-dark text-white d-flex flex-wrap">
                <tr class="col-12 d-flex flex-wrap">
                    <th scope="col" class="" style="border: none">Titel</th>
                </tr>
            </thead>
            <tbody class="d-flex flex-wrap" id="questionnaires">

            </tbody>
        </table>
        </section>
    </div>
@endsection
@section('script_after')
    <script>


        $(()=>{
            QuestionnaireOverviewService.setToken("{{csrf_token()}}");
            QuestionnaireOverviewService.setUser({{$id}});
            QuestionnaireOverviewService.loadQuestionnaires();
        });








    </script>
@endsection
