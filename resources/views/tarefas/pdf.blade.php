<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    

    <style>
        .titulo{
            border: 1px;
            background-color: gray;
            text-align: center;
            width: 100%;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .tabela {
            width: 100%;
            text-transform: uppercase;
            font-weight: bold;
        }
        
        .tabela, .tabela th {
            text-align: left;
        }

        .page-break {
            page-break-after: always;
        }       

    </style>

</head>
<body>
    
    
<div class="titulo">Tarefas</div>

<table class="tabela">
    <thead>
        <tr>
            <th>#</th>
            <th>Tarefa</th>
            <th>Data Limite Conclusão</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tarefas as $key => $tarefa)
        <tr>
            <td>{{ $tarefa->id }}</td>
            <td>{{ $tarefa->tarefa }}</td>
            <td>{{ date('d/m/Y', strtotime($tarefa->data_limite_conclusao)) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="page-break"></div>

</body>
</html>