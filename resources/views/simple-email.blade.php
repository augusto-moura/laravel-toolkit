@component('mail::message')
{!! $mensagem !!}

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent