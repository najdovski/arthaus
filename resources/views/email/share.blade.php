@component('mail::message')
# Shared activities

{{ $data->sender }} sent you a list of activities that you can
access by clicking the button below
or by visiting this url: {{ $data->url }}

@component('mail::button', ['url' => $data->url])
Visit activities
@endcomponent

@if (config('app.token_lifetime_hours'))
*Please beware that this token will
expire in {{ config('app.token_lifetime_hours') }} hours.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
