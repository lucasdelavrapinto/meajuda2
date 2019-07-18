<?php
use App\Lancamentos as Lancamento;
use App\Contas as Conta;

$lancamentos;
$month;
$date;
$nome_mes;
$pagos = number_format($pagos, 2, '.', '');
$nao_pagos = number_format($nao_pagos, 2, '.', '');

$lancamento_por_conta = Lancamento::orderby('id', 'desc')->where('tipo', 'saida')->whereMonth('data', '=', $month)->groupBy('conta')->get();
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gijgo/1.9.13/combined/css/gijgo.min.css">
	<link rel="stylesheet" href="/assets/css/sweetalert2.css">
	<link rel="stylesheet" href="/assets/css/fontawesome.css">
	<title>Laravel PHP Framework</title>
	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);

		body {
			margin:0;
			font-family:'Lato', sans-serif;
			text-align:center;
			color: #999;
		}

		a, a:visited {
			text-decoration:none;
		}

		h1 {
			font-size: 32px;
			margin: 16px 0 0 0;
		}

		.container {
			padding-top: 5%;
		}

		a {
    	color: #ff00009c;
		}

		.pointer {cursor: pointer;}
		.default {cursor: default;}

		.sidenav {
			height: 100%;
			width: 270px;
			position: fixed;
			z-index: 1;
			top: 0;
			left: 0;
			/* background-color: #343a40; */
			background-color: teal;
			overflow-x: hidden;
			padding-top: 20px;
			text-align: left;
		}

		.sidenav a {
			padding: 6px 8px 6px 16px;
			text-decoration: none;
			font-size: 17px;
			color: white;
			display: block;
		}

		.sidenav a:hover {
			color: #f1f1f1;
		}
		#myChart {
			background-color: #fff;
		}

	</style>

</head>
<body>

<div class="sidenav" style="padding-top: 300px;">
  <a href="#">Não Pagos: R$ {{ number_format(Lancamento::where('tipo', 'saida')->whereMonth('data', '=', $month)->where('confirmacao_pagamento', 'nao')->sum('valor'), 2, ',', ' ') }}</a>
  <a href="#">Pagos: R$ {{ number_format(Lancamento::where('tipo', 'saida')->whereMonth('data', '=', $month)->where('confirmacao_pagamento', 'sim')->sum('valor'), 2, ',', ' ') }}</a>
	<a href="#">Total em {{$nome_mes}}: R$ {{ number_format(Lancamento::where('tipo', 'saida')->whereMonth('data', '=', $month)->sum('valor'), 2, ',', ' ') }}</a>

	<canvas id="myChart" height="200"></canvas>

</div>

<div>
	<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
		<a class="navbar-brand mr-auto mr-lg-0" href="#">MeAjuda</a>
		<button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
			<div class="col-md-3">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções</a>
						<div class="dropdown-menu" aria-labelledby="dropdown01">
							<a class="dropdown-item" href="#" onclick="createConta();">Cadastrar Conta</a>
							<a class="dropdown-item" href="#" onclick="limparLancamentos();">Limpar Lançamentos</a>
							<a class="dropdown-item" href="#" onclick="limparContas();">Limpar Contas</a>
						</div>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#" onclick="meAjuda();">Ajuda</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</div>
	<div class="container">
		<br>
				<h2>Novo Lançamento</h2>

				<form action="{{route('cad.lan')}}" method="post">
				{{ csrf_field() }}
				<div class="row">
          <div class="col-md-2">
            <label for="firstName">Data Lançamento</label>
            <input type="text" class="form-control" id="datepicker" name="data" placeholder="" value="" required>
            <div class="invalid-feedback">
              Valid first name is required.
            </div>
          </div>
					<div class="col-md-2">
            <label for="lastName">Está Pago?</label>
						<select class="form-control" name="confirmacao_pagamento" id="select_confirmacao_pagamento">
							<option value="nao">Não</option>
							<option value="sim">Sim</option>
						</select>
            <!-- <input type="text" class="form-control" name="descricao" autocomplete="off" placeholder="" value="" required> -->
            <div class="invalid-feedback">
              Valid last name is required.
            </div>
          </div>
          <div class="col-md-2">
            <label for="lastName">Descrição</label>
            <input type="text" class="form-control" id="lastName" name="descricao" autocomplete="off" placeholder="" value="" required>
            <div class="invalid-feedback">
              Valid last name is required.
            </div>
          </div>
					<div class="col-md-2" style="display:none" id="bloco3">
            <label for="lastName"><i class="fas fa-arrow-up"></i> <i class="fas fa-arrow-down"></i></label>
						<select class="form-control" name="tipo" id="" disabled>
							<!-- <option value="entrada">Entrada</option> -->
							<option value="saida" selected>Saída</option>
						</select>
            <!-- <input type="text" class="form-control" id="lastName" placeholder="" value="" required> -->
            <div class="invalid-feedback">
              Valid last name is required.
            </div>
          </div>
					<div class="col-md-2" style="display:none" id="bloco4">
            <label for="lastName">Conta</label>

						<select class="form-control" name="conta" id="single">
							<?php $get_contas = Conta::get(); ?>
								<option value="" select>Selecione...</option>
								@foreach($get_contas as $contas)
								<option value="{{$contas->nome}}" >{{$contas->nome}}</option>
								@endforeach

						</select>

            <!-- <input type="text" class="form-control" id="lastName" name="conta" placeholder="" value="" required> -->
            <div class="invalid-feedback">
              Valid last name is required.
            </div>
          </div>
					<div class="col-md-2" style="display:none" id="bloco5">
            <label for="">Valor</label>
            <input type="text" class="form-control money" id="valor" name="valor" placeholder="" value="" required>
            <div class="invalid-feedback">
              Valid last name is required.
            </div>
          </div>
					<br>
					<div class="col-md-12" style="display:none" id="bloco6">
					<!-- <label for="">Salvar</label> -->
					<input type="submit" value="Adicionar Lançamento" class="btn btn-info form-control">

          </div>
        </div>
				</form>

		<br>
		<h2>Lançamentos do mês</h2>
      <div class="table-responsive">

				<table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>Data</th>
              <th>Descrição</th>
              <th>Entrada/Saída</th>
              <th>Conta</th>
              <th>Valor</th>
							<th>Pago?</th>
							<th></th>
            </tr>
          </thead>

					<tbody>
						@foreach($lancamentos as $lan)
						<tr>
							<td id="{{$lan->id}}" class="mydata"> {{ date('d/m/Y', strtotime(str_replace('-','/', $lan->data))) }}</td>
							<td>{{ ucfirst($lan->descricao) }}</td>
							<td>
								@if( $lan->tipo == 'saida' )
									Saída
								@else
									Entrada
								@endif
							</td>

							<td>{{ ucfirst($lan->conta)}}</td>
							<td>R$ {{number_format($lan->valor, 2, ',', ' ')}} </td>

							<td id="{{$lan->id}}" class="confpag">
								@if($lan->confirmacao_pagamento == 'sim')
									<i class="fas fa-check"></i> Sim
								@else
									<i class="fas fa-times"></i> Não
								@endif
							</td>

							<td>
								<a href="#" onclick="confirmacao('<?php echo Crypt::encrypt($lan->id); ?>', '<?php echo $lan->descricao; ?>');">
									<i class="fas fa-trash-alt"></i>
								</a>
							</td>
						</tr>
						@endforeach

          </tbody>

        </table>
      </div>

		<br>

		<br>
		<h2>Despesas por Conta</h2>

		<style>

		.card-text:last-child {
			font-style: italic;
		}
		.card-body{
			border: none;
			border-radius: 10px;
			font-weight: 500;
			font-size: 35px;
			line-height: 0.8;
			letter-spacing: -0.012em;
			background-color: #343a40;
			color: #00ffe3
		}
		</style>

		<div class="container">
			<div class="row">
			@foreach($lancamento_por_conta as $slbc)
				<div class="card" style="width: 17rem;">
					<div class="card-body">
						<h5 class="card-title">{{ ucfirst($slbc->conta) }}</h5>
						<p class="card-text">R$ {{ number_format(DB::table('lancamentos')->where('tipo', 'saida')->where('conta', $slbc->conta)->whereMonth('data', '=', $month)->sum('valor'), 2, ',', ' ') }}</p>
					</div>
				</div>
				@endforeach
			</div>
		</div>

		<br><br>
	</div>


	<!-- Modal nova data -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="col-md-6 offset-md-3">
            <label for="firstName">Nova Data</label>
            <input type="text" class="form-control" id="datepicker2" name="data" placeholder="" value="" required>
            <div class="invalid-feedback">
              Valid first name is required.
            </div>
          </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" id="btn_salvar_novadata" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal confirmacao pagamanto -->
	<div class="modal fade" id="ModalConfirmapagamento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="col-md-6 offset-md-3">
            <label for="lastName">Está Pago?</label>
						<select class="form-control" name="select_confirmacao_pagamento" id="confirmacao_pagamento">
							<option value="nao">Não</option>
							<option value="sim">Sim</option>
						</select>
          </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" id="btn_salvar_confirmacao_pagamento" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>


	<!-- Bootstrap & Jquery -->
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

	<!-- DateFormat -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/gijgo/1.9.13/combined/js/gijgo.min.js"></script>
	<script src="https://unpkg.com/gijgo@1.9.13/js/messages/messages.pt-br.js" type="text/javascript"></script>

	<!-- Masked -->
	<script type="text/javascript" src="/assets/js/masked.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.priceformat.min.js"></script>

	<!-- Minhas Funções -->
	<script type="text/javascript" src="/assets/js/functions.js"></script>

	<!-- SweeAlert2 -->
	<script type="text/javascript" src="/assets/js/sweetalert2.js"></script>

	<!-- FontAwesome -->
	<script src="https://kit.fontawesome.com/5d8ee5c07e.js"></script>

	<!-- Charts JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>


	<script>
	$(document).ready(function (){
		$('.mydata, .confpag').mouseover(function (e){
			var meuid = e.target.id;
			// $("#span_"+meuid).show();

			$(this).css('color', 'red');
			$(this).addClass('pointer');
		})
		.mouseout(function(e) {
			var meuid = e.target.id;
			// $("#span_"+meuid).hide();

			$(this).css('color', '#999');
			$(this).removeClass('pointer');
		});



		$("#lastName").focus();

		initMaskMoney();
		var today = new Date().toLocaleDateString();

		$("#datepicker").datepicker({
        uiLibrary: 'bootstrap4',
        locale: 'pt-br',
        format: 'dd/mm/yyyy',
        value: today
		});

		$("#datepicker2").datepicker({
        uiLibrary: 'bootstrap4',
        locale: 'pt-br',
        format: 'dd/mm/yyyy',
        value: today
		});

		limparCampos();

		$("#single").val("Selecione...");
		$("#single").val("").change();

		$('#lastName').keyup(function () {
			var campos = $(this).val();
			verificaCampoPreenchidos(campos);
		});

		function displayVals(data) {
			if (data) {
				$("#bloco5").show('slow');
			}else{
				$("#bloco5").hide('slow');
				$("#bloco6").hide('slow');
			}
		}

		$("#single").change(function(){
			data = $(this).val();
			displayVals(data);
		})

		function verificaCampoPreenchidos(data) {
			if (data.length > 0) {
				$("#bloco3").show('slow');
				$("#bloco4").show('slow');
			}else{
				$("#bloco3").hide('slow');
				$("#bloco4").hide('slow');
			}
		}

		$("input#valor").keyup(function(){
			var data = $(this).val();
			var res = data.replace("R$ ", "");
			if(res.length >= 7){
				var b = res.replace(".","");
				var a = b.replace(",",".");
    	}else{
				a = res.replace(",",".");
			}
			//return a
			if (a > 0) {
				$("#bloco6").show('slow');
			} else {
				$("#bloco6").hide('slow');
			}
		});

		var ctx = $('#myChart');
		var myBarChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ["Pagos", "Não Pagos"],
      datasets: [
        {
          label: "R$",
          backgroundColor: ["#3e95cd", "#8e5ea2"],
          data: [ {{ $pagos }}, {{ $nao_pagos }}]
        }
      ]
		},

    options: {
			legend: { display: false },
      title: {
        display: true,
        text: 'Parcelas pagas e não-pagas'
			},
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
      }
    }
});

		// end document ready
	});


	</script>
</body>
</html>
