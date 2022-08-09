<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PSI CESAV</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">CESAV</h1>
            <p class="lead">Dashboard de Contratos Inadimplentes</p>
        </div>
    </div>

    <div class="container" width="100%">
        <div class="col d-flex justify-content-space-between">

            <div class="card">
                <div class="card-body">
                    <form id="pesquisar">
                        @csrf
                        <div class="form-group">
                            <label for="data_inicial">Data Inicial</label>
                            <input type="date" class="form-control" id="data_inicial" name="data_inicial">
                        </div>
                        <div class="form-group">
                            <label for="data_final">Data Final</label>
                            <input type="date" class="form-control" id="data_final" name="data_final">
                        </div>
                        <div class="form-group">
                            <label for="unidade">Unidade</label>
                            <br>
                            <select class="form-select" name="unidade" id="unidade">
                                <option value="todas_unidades">TODAS</option>
                                @foreach($unidades as $unidade)
                                <option value="{{$unidade->co_unidade}}">{{$unidade->no_unidade}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="produto">Produto</label>
                            <br>
                            <select class="form-select" name="produto" id="produto">
                                <option value="todos_produtos">TODOS</option>
                                @foreach($produtos as $produto)
                                <option value="{{$produto->nu_produto}}">{{$produto->no_produto}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" id="btnPesquisar" class="btn btn-primary">Pesquisar</button>
                    </form>
                </div>
            </div>

            <div class="card" id="filtro-tabela">
                <div class="card-header">
                    <label for="produto">Unidade</label>
                    <select class="form-select" name="display_unidade" id="display_unidade">
                    </select>

                    <label for="display_produto">Produto</label>
                    <select class="form-select" name="display_produto" id="display_produto">
                    </select>

                    <label for="display_data">Data</label>
                    <select class="form-select" name="display_data" id="display_data">
                    </select>
                    <br>
                </div>
                <div class="card-body">
                    <table class="table" id="tabela_por_data">
                        <thead>
                            <tr>
                                <th scope="col">Data</th>
                                <th scope="col">Cod. Produto</th>
                                <th scope="col">Quantidade de Contratos</th>
                                <th scope="col">Valor Base</th>
                                <th scope="col">Unidade</th>
                            </tr>
                        </thead>
                        <tbody id="corpo">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col d-flex justify-content-bottom">
            <div class="card" style="width:52rem;">
                <div class="card-body" id="corpo-canvas">
                    <div>
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>



        <div class="col d-flex justify-content-bottom">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Última posição: {{ $data_posicao }}</h5>
                    <p class="card-text">Quantidade de Contratos: {{$qtd_contratos}}</p>
                    <p class="card-footer">Posição Dívida Consolidada: {{$valor_posicao}}</p>
                </div>
            </div>
        </div>



    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{URL::asset('js/tabela-dinamica.js')}}"></script>

</body>

<script type="text/javascript">
    $(document).ready(function() {
        var ctr;
        var contratos;
        var myChart;
        var url = "{{url('/pesquisar')}}";

        $('#pesquisar').on('submit', function(e) {
            e.preventDefault();

            data_inicial = $('#data_inicial').val();
            data_final = $('#data_final').val();
            co_unidade = $('#unidade option:selected').val();
            nu_produto = $('#produto option:selected').val();

            console.log(co_unidade);
            console.log(nu_produto);

            console.log()

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
                success: function(response) {

                    ctr = response;

                    //organiza, cria e inserer listeners nos campos para filtrar a tabela de contratos
                    organizaCampos(ctr, 1);
                    organizaCampos(ctr, 2);
                    organizaCampos(ctr, 3);

                    //verifica se a opção todos está marcado, pois caso esteja, ira informar sobre a primeira unidade/produto e o usuário poderá ir selecionando as informações que deseja
                    if (co_unidade == "todas_unidades" && nu_produto == "todos_produtos") {
                        primeiraUnidade = ctr.filter(function(el) {
                            return el.co_unidade == 1 && el.nu_produto == 1 && el.data_arquivo == data_inicial;
                        });
                        adicionaContratoNaTabela(primeiraUnidade);
                        resetarCanvas(ctr, 1);
                    } else if (co_unidade == "todas_unidades") {
                        primeiraData = ctr.filter(function(el) {
                            return el.data_arquivo == data_inicial;
                        });
                        adicionaContratoNaTabela(primeiraData);
                        resetarCanvas(ctr, 2);
                    } else if (nu_produto == "todos_produtos") {
                        primeiraData = ctr.filter(function(el) {
                            return el.data_arquivo == data_inicial;
                        });
                        adicionaContratoNaTabela(primeiraData);
                        resetarCanvas(ctr, 3);
                    } else {
                        primeiraData = ctr.filter(function(el) {
                            return el.data_arquivo == data_inicial;
                        });
                        adicionaContratoNaTabela(primeiraData);
                        resetarCanvas(ctr, 4);
                    }
                },
                error: function(error) {
                    alert('error; ' + eval(error));
                }
            });
        });

        //Adiciona listener na div dos filtros para ouvir as mudanças dos nodes filhos e aplica-las na tabela
        filtros = document.getElementById('filtro-tabela').addEventListener('change', e => {
            e.stopPropagation;

            var f1 = document.getElementById('display_unidade').value;
            var f2 = document.getElementById('display_produto').value;
            var f3 = document.getElementById('display_data').value;


            ctr1 = ctr.filter(function(el) {
                return el.co_unidade == f1 && el.nu_produto == f2 && el.data_arquivo == f3;
            });
            adicionaContratoNaTabela(ctr1);
        }, {
            capture: true
        });
    });

    function organizaCampos(contratos, tipo) {
        switch (tipo) {
            case 1:
                campo_unidade = [...new Set(contratos.map(contrato => contrato.co_unidade))];
                criaCampos(ordenar(campo_unidade), 1);
                break;

            case 2:
                campo_produto = [...new Set(contratos.map(contrato => contrato.nu_produto))];
                criaCampos(ordenar(campo_produto), 2);
                break;

            case 3:
                campo_data = [...new Set(contratos.map(contrato => contrato.data_arquivo))];
                criaCampos(ordenar(campo_data), 3);
                break;
        }
    }

    function ordenar(campo) {
        campo.sort(function(a, b) {
            return a - b;
        });
        return campo;
    }

    function criaCampos(campo, tipo) {
        let display
        switch (tipo) {
            case 1:
                display = document.getElementById('display_unidade');
                criaListenerDeSelect(display);
                break;
            case 2:
                display = document.getElementById('display_produto');
                criaListenerDeSelect(display);
                break;
            case 3:
                display = document.getElementById('display_data');
                criaListenerDeSelect(display);
                break;
        }

        campo.forEach(function(valor) {
            var opcao = montaOption(valor, valor);
            display.appendChild(opcao);
        });
    }

    //Esse listener irá disparar o evento para o filtro tabela (table/tbody)

    function criaListenerDeSelect(el) {
        let valor;
        el.setAttribute("onchange", "getvalSelect(this);")
        el.addEventListener("change",
            function() {
                return valor = el;
            });
    }

    function getvalSelect(sel) {
        return (sel.value);
    }

    function geraGrafico(contratos, tipo) {
        let eixoX;
        let eixoY;

        switch (tipo) {
            case 1:
                eixoY = [];
                dados_por_data = groupBy(contratos, "data_arquivo");
                dados_por_data.forEach(dado => {
                    let valor_base_dia = 0;
                    dado.forEach(valor => {
                        valor_base_dia += Number(valor.valor_base.replace(/[^0-9.-]+/g, ""));
                    });
                    eixoY.push(valor_base_dia);
                });
                break;
            case 2:
                break;
            case 3:
                break;
            case 4:
                break;
        }

        eixoX = [...new Set(contratos.map(contrato => contrato.data_arquivo))];
        // eixoY = [...new Set(contratos.map(contrato => contrato.valor_base))];

        const data = {
            labels: eixoX,
            datasets: [{
                label: 'Valor Total por Data',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255,99,132)',
                data: eixoY
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {}
        };

        myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    }

    var resetarCanvas = function(contratos, tipo) {
        $('#myChart').remove(); // this is my <canvas> element
        $('#corpo-canvas').append('<canvas id="myChart"><canvas>');
        geraGrafico(contratos, tipo);
    };

    function groupBy(arr, prop) {
        const map = new Map(Array.from(arr, obj => [obj[prop],
            []
        ]));
        arr.forEach(obj => map.get(obj[prop]).push(obj));
        return Array.from(map.values());
    }
</script>

</html>