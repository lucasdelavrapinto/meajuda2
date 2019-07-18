$('.mydata').click(function (e) {
  var meuid = e.target.id;
  var minhanovadata = $('#datepicker2').val();

  $('#exampleModal').modal('show');

  $('#datepicker2').change(function () {
    minhanovadata = $(this).val();
  });

  $('#btn_salvar_novadata').click(function () {
    saveNovaData(meuid, minhanovadata);
  });
});

function saveNovaData(id, data) {
  $.ajax({
    type: 'POST',
    url: '/altera-data',
    data: { minhanovadata: data, meuid: id },
    success: function (response) {
      if (response == 'ok') {
        Swal.fire({
          position: 'center',
          type: 'success',
          title: 'Sua alteração foi salva!',
          showConfirmButton: false,
          timer: 1500
        });
        location.reload();
      }
    }
  });
}

$(".confpag").click(function (e) {
  var meuid = e.target.id;
  var minharesposta = $('select[name = select_confirmacao_pagamento]').val();

  $("#ModalConfirmapagamento").modal('show');
  // console.log(meuid);
  // console.log(minharesposta);

  $('select[name = select_confirmacao_pagamento]').change(function () {
    minharesposta = $(this).val();
  });

  $("#btn_salvar_confirmacao_pagamento").click(function () {
    saveNovaConfirmacaoPagamento(meuid, minharesposta);
  });
});

function saveNovaConfirmacaoPagamento(id, data) {
  $.ajax({
    type: 'POST',
    url: '/altera-confirmacao-pagamento',
    data: { minharesposta: data, meuid: id },
    success: function (response) {
      if (response == 'ok') {
        Swal.fire({
          position: 'center',
          type: 'success',
          title: 'Sua alteração foi salva!',
          showConfirmButton: false,
          timer: 1500
        });
        location.reload();
      }
    }
  });
}




function confirmacao(id, descricao) {
  Swal.fire({
    title: 'Deseja remover?',
    text: 'Lançamento: ' + descricao,
    showCancelButton: true,
    confirmButtonText: 'Sim'
  }).then(result => {
    if (result.value) {
      window.location = '/deleta-lancamento/' + id + "')";
    }
  });
}

function initMaskMoney() {
  $('.money').priceFormat({
    prefix: 'R$ ',
    centsSeparator: ',',
    thousandsSeparator: '.'
  });
}

function limparCampos() {
  $('#lastName').val('');
  $('#valor').val('R$ 0,00');
}

function formatData(date) {
  options = { day: 'numeric', month: 'numeric', year: 'numeric' };
  data = new Date(date).toLocaleString('pt-BR', options);
  return data;
}

function formatValor(valor) {
  var val = valor.toFixed(2).replace('.', ',');
  return val;
}

$('#btn_salvar').click(function (e) {
  e.preventDefault();
  var serializeDados = $('#form_cadastra_lancamento').serialize();

  Swal.fire({
    title: 'Deseja Salvar?',
    showCancelButton: true,
    confirmButtonText: 'Salvar'
  }).then(result => {
    if (result.value) {
      $.ajax({
        type: 'POST',
        url: '/cadastra-lancamento',
        data: serializeDados,
        success: function (response) {
          location.reload();
        }
      });
      console.log('Salvo');
    }
  });
});

function createConta() {
  Swal.fire({
    title: 'Informe o nome da sua conta',
    input: 'text',
    text: 'exemplo: Carteira, Banco do Brasil, Itaú, ...',
    inputAttributes: {
      autocapitalize: 'off'
    },
    showCancelButton: true,
    confirmButtonText: 'Salvar'
  }).then(result => {
    if (result.value) {
      $.ajax({
        type: 'POST',
        url: '/cadastra-conta',
        data: { input: result.value },
        cache: false,
        success: function (response) {
          if (response == 'ok') {
            console.log('conta salva');
            location.reload();
          }
        },
        failure: function (response) {
          swal(
            'Internal Error',
            'Oops, your note was not saved.', // had a missing comma
            'error'
          );
        }
      });
    }
  });
}

function limparLancamentos() {
  Swal.fire({
    title: 'Deseja Excluir Tudo?',
    text: 'Cuidado, essa ação é irreversível',
    showCancelButton: true,
    confirmButtonText: 'Sim, eu quero!'
  }).then(result => {
    console.log(result.value);
    if (result.value) {
      $.ajax({
        type: 'POST',
        data: '',
        url: '/deletar-lancamentos/todos',
        success: function (response) {
          if (response == 'ok') {
            Swal.fire('Sucesso!');
            location.reload();
          }
        }
      });
    }
  });
}

function limparContas() {
  Swal.fire({
    title: 'Deseja excluir todas as contas cadastradas?',
    text: 'Cuidado, essa ação é irreversível',
    showCancelButton: true,
    confirmButtonText: 'Sim, eu quero!'
  }).then(result => {
    console.log(result.value);
    if (result.value) {

      $.ajax({
        type: 'POST',
        data: '',
        url: '/deletar-contas/todos',
        success: function (response) {
          if (response == 'ok') {
            Swal.fire('Sucesso!');
            location.reload();
          }
        }
      });
    }
  });
}

function meAjuda() {
  Swal.fire(
    'Dica:',
    'Para editar uma valor, basta posicionar o cursor do mouse encima, se esse valor mudar de cor significa que você pode alterá-lo, então basta clicar :) ',
    'question'
  );
}
