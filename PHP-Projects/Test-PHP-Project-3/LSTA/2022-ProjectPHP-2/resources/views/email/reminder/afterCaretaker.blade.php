@extends('email.reminder.template')
@section('title')
    Reminder voor taak: {{$displayTask['name']}} (meer dan 30 minuten geleden)
@endsection
@section('patient')
    <p>Patient: {{$displayTask['patientName']}}</p>
@endsection
