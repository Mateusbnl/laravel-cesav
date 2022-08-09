var ctr;
var contratos;
var url = "{{url('/pesquisar')}}";

$('#pesquisar').on('submit', function (e) {
    e.preventDefault();

    data_inicial = $('#data_inicial').val();
    data_final = $('#data_final').val();
    co_unidade = $('#unidade option:selected').val();
    nu_produto = $('#produto option:selected').val();

    $.ajax({
        url: url,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': "{{csrf_token()}}",
        },
        contentType: 'application/x-www-form-urlencoded',
        data: {
            "data_inicial": data_inicial,
            "data_final": data_final,
            "unidade": co_unidade,
            "produto": nu_produto
        },
        success: function (response) {

            ctr = response;

            //organiza e cria os campos para filtrar a tabela de contratos por unidade, produto e data
            organizaCampos(ctr, 1);
            organizaCampos(ctr, 2);
            organizaCampos(ctr, 3);

            //verifica se a opção todos está marcado, pois caso esteja, ira informar sobre a primeira unidade/produto e o usuário poderá ir selecionando as informações que deseja
            if (co_unidade == "todas_unidades" && nu_produto == "todos_produtos") {
                primeiraUnidade = ctr.filter(function (el) {
                    return el.co_unidade == 1 && el.nu_produto == 1 && el.data_arquivo == data_inicial;
                });
                adicionaContratoNaTabela(primeiraUnidade);
            } else {
                adicionaContratoNaTabela(ctr);
            }
        },
        error: function (error) {
            alert('error; ' + eval(error));
        }
    });
});
