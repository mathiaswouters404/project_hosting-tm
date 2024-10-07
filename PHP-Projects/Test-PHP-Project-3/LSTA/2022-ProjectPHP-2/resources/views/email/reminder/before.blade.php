@extends('email.reminder.template')
@section('title')
    Reminder voor taak: {{$displayTask['name']}} (binnen minder dan 30 minuten)
@endsection
