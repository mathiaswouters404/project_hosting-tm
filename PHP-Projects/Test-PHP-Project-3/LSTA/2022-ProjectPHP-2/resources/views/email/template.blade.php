@component('mail::message')
    Beste {{ $toName }}
    <div>
        {{ $body }}
    </div>

    Bedankt,</br>
    {{ env('APP_NAME') }}
@endcomponent
