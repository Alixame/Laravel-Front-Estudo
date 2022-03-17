<?php

use App\Mail\MensagemTesteMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);
/*
Route::middleware('verified')->get('/home', 'HomeController@index')->name('home');
*/
Route::middleware('verified')->resource('tarefa', 'TarefaController');

Route::get('tarefa/exportacao', 'TarefaController@exportacao')->name('tarefa.exportacao');

Route::middleware('verified')->get('/mensagem-teste', function () {
    //return new MensagemTesteMail();

    Mail::to('lucasali2003@gmail.com')->send(new MensagemTesteMail);
    return 'mensagem enviada com sucesso!';
});