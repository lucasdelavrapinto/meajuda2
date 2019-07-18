<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Lancamentos as Lancamento;
use App\Contas as Conta;

use Crypt;
use Redirect;

use DateTime;


function jsonReturn($data){
	header("Content-Type: application/json; Charset=UTF-8");
	echo json_encode($data);
	return exit();
}

function retornaNomeMes($month){
    if ($month == '01') {
        $mes = 'Janeiro';
    } else if ($month == '02') {
        $mes = 'Fevereiro';
    }else if ($month == '03') {
        $mes = 'Março';
    }else if ($month == '04') {
        $mes = 'Abril';
    }else if ($month == '05') {
        $mes = 'Maio';
    }else if ($month == '06') {
        $mes = 'Junho';
    }else if ($month == '07') {
        $mes = 'Julho';
    }else if ($month == '08') {
        $mes = 'Agosto';
    }else if ($month == '09') {
        $mes = 'Setembro';
    }else if ($month == '10') {
        $mes = 'Outubro';
    }else if ($month == '11') {
        $mes = 'Jovembro';
    }else if ($month == '12') {
        $mes = 'Dezembro';
    } else {
        $mes = '';
    }
    return $mes;
}

class HomeController extends Controller
{
    public function showWelcome()
	{
		$date = new Datetime('now');
        $month = $date->format('m');
        $nome_mes = retornaNomeMes($month);

				$lancamentos = Lancamento::orderby('data', 'desc')->whereMonth('data', '=', $month)->get();

				$pagos = Lancamento::where('tipo', 'saida')->whereMonth('data', '=', $month)
														->where('tipo', 'saida')
														->where('confirmacao_pagamento', 'sim')
														->sum('valor');

				$nao_pagos = Lancamento::where('tipo', 'saida')->whereMonth('data', '=', $month)
														->where('tipo', 'saida')
														->where('confirmacao_pagamento', 'nao')
														->sum('valor');

        return view('sistema.index')
            ->with('lancamentos', $lancamentos)
            ->with('month', $month)
						->with('date', $date)
						->with('pagos', $pagos)
						->with('nao_pagos', $nao_pagos)
            ->with('nome_mes', $nome_mes);
	}

	public function buscaLancamentos()
	{
		$lancamentos = Lancamento::orderby('data', 'desc')->get();
		return $lancamentos;
	}

	public function getLancament()
	{
		$lancamentos = Lancamento::orderby('id', 'desc')
		->groupBy('conta')
		->toArray();
		return $lancamentos;

	}

	public function cadastrarLancamento(Request $request)
	{
		function strReplace($variable){
			if(strlen($variable) >= 7){
				$b = str_replace('.','',$variable);
				$a = str_replace(',','.',$b);
			}else{
				$a = str_replace(',','.',$variable);
			}
			return $a;
        }

        $data_passada = $request->input('data');
		$data = date('Y-m-d', strtotime(str_replace('/','-', $data_passada)));


        $descricao = $request->input('descricao');

        $tipo = $request->input('tipo');
        $conta = $request->input('conta');

        $confirm_pag = $request->input('confirmacao_pagamento');

        $valor_passado = $request->input('valor');	//R$ 123,45
		$vlr = str_replace('R$ ','', $valor_passado);
		$valor = strReplace($vlr);

		if ($request->input('tipo') == null) {
			$tipo = "saida";
		}

		if ($request->input('conta') == null) {
			$conta = "Geral";
		}

		if ($request->input('descricao') == null) {
			$descricao = "Não Informado";
        }

        if($request->input('confirmacao_pagamento') == null) {
            $confirmacao_pagamento = "Não Informado";
        }

		$lancamento = new Lancamento();
		$lancamento->data = $data;
		$lancamento->descricao = $descricao;
		$lancamento->tipo = $tipo;
        $lancamento->conta = $conta;
        $lancamento->confirmacao_pagamento = $confirm_pag;
		$lancamento->valor = $valor;
		$lancamento->save();

		return back();

	}

	public function cadastrarConta()
	{
		$conta = new Conta();
		$conta->nome = $_POST['input'];
		$conta->save();
		return 'ok';

	}

	public function deletaLancamento($id)
	{
		$id = Crypt::decrypt($id);
		$lancamento = Lancamento::find($id);
		if ($lancamento) {
			$lancamento->delete();
			return Redirect::back();
		}

	}

	public function alteraData(Request $request)
	{

		$novadata = date('Y-m-d', strtotime(str_replace('/','-', $request->input('minhanovadata'))));
		$id = $request->input('meuid');
		$lancamento = Lancamento::find($id);
		$lancamento->data = $novadata;
		$lancamento->save();
		return 'ok';

	}

	public function alteraConfirmacaoPagamento(Request $request)
	{
		$novadata = $request->input('minharesposta');
		$id = $request->input('meuid');
		$lancamento = Lancamento::find($id);
		$lancamento->confirmacao_pagamento = $novadata;
		$lancamento->save();
		return 'ok';
	}

	public function deletarLancamentosAll()
	{
		Lancamento::truncate();
		return 'ok';
	}

	public function deletarContasAll()
	{
		Conta::truncate();
		return 'ok';
	}

	public function phpinfo(){
		return View::make('phpinfo');
	}
}
