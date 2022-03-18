<?php

namespace App\Http\Controllers;

use App\Mail\NovaTarefaMail;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TarefasExport;
use App\Models\User;
use PDF;

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

    
    // METODO CRIADO A PARTIR DO PACOTE 'EXEL' PARA EXPORTAR ARQUIVOS DO TIPO 'XLSX', 'CSV' E 'PDF'
    public function exportacao($extensao) {

        // VERIFICANDO SE A EXTENSÃO ESCOLHIDA É PERMITIDA
        if (in_array($extensao, ['xlsx', 'csv', 'pdf'])) {

            // RETORNANDO ARQUIVO COM A EXTENSÃO DESEJADA
            return Excel::download(new TarefasExport,  'tarefas.'.$extensao);

        }
        
        // SE NÃO PASSAR NA VALIDÇÃO RETORNA PAGINA INDEX
        return redirect()->route('tarefa.index');

    }

    // METODO CRIADO A PARTIR DO PACOTE 'DOMPDF' (EXPECIFICO PARA PDF)
    public function exportar() {

        //  RECUPERANDO DA DOS DO USUARIO AUTENTICADO
        $usuario = User::find(auth()->user()->id);

        // RECURAPERANDO TAREFAS RELACIONADAS AO USUARIO
        $tarefas =  $usuario->tarefas()->get();

        // DEFININDO VIEW QUE SERÁ GERADA COMO PDF E PASSANDO VARIAVEIS
        $pdf = PDF::loadView('tarefas.pdf', ['tarefas' => $tarefas]);
        
        // DEFININDO TIPO DE PAPEL E ORIENTAÇÃO DA PAGINA A SER IMPRESSA
        // tipos de papeis: a4, letter
        // orientação: landscape (paisagem), portrait(retrato) <- PADRÃO
        $pdf->setPaper('a4','landscape');

        // RETORNANDO O DOWNLOAD DO ARQUIVO DIRETAMENTE
        //return $pdf->download('tarefa.pdf');
        
        // RETORNA A TELA DE IMPRESSÃO PARA PRE-VISUALIZAÇÃO
        return $pdf->stream('tarefa.pdf');

    }

}
