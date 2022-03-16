@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="h2 text-center">Tarefas</div>

            <div class="card">
                <div class="card-header">
                    <a href="{{ route('tarefa.create')}}" class="btn btn-success">Criar</a>
                </div>

                <div class="card-body">


                    
                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tarefa</th>
                            <th scope="col">Data Limite Conclusão</th>
                            <th scope="col" class="text-center">Ação</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($tarefas as $tarefa)
                            <tr>
                                <th scope="row">{{$tarefa->id}}</th>
                                <td>{{$tarefa->tarefa}}</td>
                                <td>{{date('d/m/Y', strtotime($tarefa->data_limite_conclusao))}}</td>
                                <td class="text-center">
                                    <a href="{{ route('tarefa.show', $tarefa->id) }}">Vizualizar</a>
                                    <a href="{{ route('tarefa.edit', $tarefa->id) }}">Alterar</a>
                                    <form id="form_{{$tarefa->id}}" method="POST" action="{{ route('tarefa.destroy',['tarefa' => $tarefa->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <a href="#" onclick="document.getElementById('form_{{$tarefa->id}}').submit()">Excluir</a>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                      </table>

                    <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="{{ $tarefas->previousPageUrl() }}"><</a></li>
                        @for ($i = 1; $i <= $tarefas->lastPage(); $i++)
                        <li class="page-item {{ $tarefas->currentPage() == $i ? 'active' : '' }}"><a class="page-link" href="{{ $tarefas->url($i) }}">{{ $i }}</a></li>    
                        @endfor                    
                        <li class="page-item"><a class="page-link" href="{{ $tarefas->nextPageUrl() }}">></a></li>
                    </ul>
                    </nav>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
