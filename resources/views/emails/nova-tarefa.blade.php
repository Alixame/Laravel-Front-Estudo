@component('mail::message')
# {{ $tarefa }}

Data Limite de Conclusão: {{ $data_limite_conclusao }}

@component('mail::button', ['url' => $url])
Ver
@endcomponent

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
