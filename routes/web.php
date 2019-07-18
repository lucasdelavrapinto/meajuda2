<?php

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

// route to show the login form
Route::get('/', array('as' => 'index','uses' => 'HomeController@showWelcome'));
Route::get('/busca-lancamento', array('as' => 'bus.lan','uses' => 'HomeController@buscaLancamentos'));
Route::get('/get-lan', array('as' => 'get.lan','uses' => 'HomeController@getLancament'));

Route::get('/php-info', array('as' => 'php.info','uses' => 'HomeController@phpinfo'));

Route::post('/cadastra-lancamento', array('as' => 'cad.lan','uses' => 'HomeController@cadastrarLancamento'));
Route::post('/altera-data', array('as' => 'alt.dat','uses' => 'HomeController@alteraData'));
Route::post('/altera-confirmacao-pagamento', array('as' => 'alt.conf.pag','uses' => 'HomeController@alteraConfirmacaoPagamento'));

Route::any('deleta-lancamento/{id}', array('as' => 'del.lan', 'uses' => 'HomeController@deletaLancamento'));


Route::post('/cadastra-conta', array('as' => 'cad.con','uses' => 'HomeController@cadastrarConta'));

Route::any('/deletar-lancamentos/todos', array('as' => 'del.all','uses' => 'HomeController@deletarLancamentosAll'));
Route::any('/deletar-contas/todos', array('as' => 'del.all','uses' => 'HomeController@deletarContasAll'));