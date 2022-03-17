<?php

namespace App\Http\Controllers;

use App\Mail\NovaTarefaMail;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TarefasExport;

use function PHPUnit\Framework\returnValueMap;

class TarefaController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    } 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;

        $tarefas = Tarefa::where('user_id', $user_id)->paginate(10);

        return view('tarefas.index', ['tarefas' => $tarefas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tarefas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dados = $request->all();
        $dados['user_id'] = auth()->user()->id;

        $tarefa = Tarefa::create($dados);

        $detinatario = auth()->user()->email;

        Mail::to($detinatario)->send(new NovaTarefaMail($tarefa));

        return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function show(Tarefa $tarefa)
    {
        return view('tarefas.show', ['tarefa' => $tarefa]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function edit(Tarefa $tarefa)
    {   

        if($tarefa->user_id == auth()->user()->id){

            return view('tarefas.edit', ['tarefa' => $tarefa]);

        }

        return redirect()->route('tarefa.index');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tarefa $tarefa)
    {
        if($tarefa->user_id == auth()->user()->id){

            $tarefa->update($request->all());

            return redirect()->route('tarefa.show', $tarefa->id);
        
        }

        return redirect()->route('tarefa.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tarefa $tarefa)
    {
        if($tarefa->user_id == auth()->user()->id){
            
            $tarefa->delete();
            
            return redirect()->route('tarefa.index');
        }

        return redirect()->route('tarefa.index');
    }

    public function exportacao() {
        return Excel::download(new TarefasExport, 'tarefas.xlsx');
    }

}
