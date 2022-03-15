@component('mail::message')
# Introdução

o corpo da mensagem é aqui

@component('mail::button', ['url' => ''])
texto do botão
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
