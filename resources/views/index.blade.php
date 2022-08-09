<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PSI CESAV</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

</head>

<body>
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">CESAV</h1>
            <p class="lead">Dashboard de Contratos Inadimplentes</p>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row mx-md-n5 align-items-start">
            <div class="col px-md-5" style="height: 10.0%;">
                <div class="card p-3 border bg-light">
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
            </div>

            <div class="col px-md-5">
                <div class="p-3 border bg-light" id="filtro-tabela">
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

            <div class="col px-md-5">
                <div class="card p-3 border bg-light">
                    <div class="card-body" id="corpo-canvas">
                        <canvas class="canvas" id="myChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col px-md-5">
                <div class="card p-3 border bg-light">
                    <div class="card-body" id="corpo-pie">
                        <canvas class="canvas" id="myPieChartProdutos"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row mx-md-n5 align-items-start">
            <div class="col-4 px-md-5">
                <div class="card p-3 border bg-light">
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
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

</body>

<script type="text/javascript">
    $(document).ready(function() {
        var ctr;
        var contratos;
        var myChart;
        var myPieChartProdutos;
        var url = "{{url('/pesquisar')}}";

        table = $('#tabela_por_data').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json"
            }
        });

        $('#pesquisar').on('submit', function(e) {
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
                success: function(response) {

                    ctr = response;
                    //verifica se a opção todos está marcado, pois caso esteja, ira informar sobre a primeira unidade/produto e o usuário poderá ir selecionando as informações que deseja
                    if (co_unidade == "todas_unidades" && nu_produto == "todos_produtos") {
                        primeiraUnidade = ctr.filter(function(el) {
                            return el.co_unidade == 1 && el.nu_produto == 1 && el.data_arquivo == data_inicial;
                        });
                        // adicionaContratoNaTabela(primeiraUnidade);
                        resetarCanvas(ctr, 1);
                    } else if (co_unidade == "todas_unidades") {
                        primeiraData = ctr.filter(function(el) {
                            return el.data_arquivo == data_inicial;
                        });
                        // adicionaContratoNaTabela(primeiraData);
                        resetarCanvas(ctr, 1);
                    } else if (nu_produto == "todos_produtos") {
                        primeiraData = ctr.filter(function(el) {
                            return el.data_arquivo == data_inicial;
                        });
                        // adicionaContratoNaTabela(primeiraData);
                        resetarCanvas(ctr, 1);
                    } else {
                        primeiraData = ctr.filter(function(el) {
                            return el.data_arquivo == data_inicial;
                        });
                        // adicionaContratoNaTabela(primeiraData);
                        resetarCanvas(ctr, 1);
                    };

                    table.destroy();
                    table = $('#tabela_por_data').DataTable({
                        data: ctr,
                        columns: [{
                                data: 'data_arquivo'
                            },
                            {
                                data: 'nu_produto'
                            },
                            {
                                data: 'qtd'
                            },
                            {
                                data: 'valor_base'
                            },
                            {
                                data: 'co_unidade'
                            }
                        ],
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json"
                        }
                    });
                },
                error: function(error) {
                    alert('error; ' + eval(error));
                }
            });
        });

        /**Função para gera Gráficos conforme dados e tipo de pesquisa */
        function geraGrafico(contratos, tipo) {
            let eixoX;
            let eixoY;
            let pieLabel;
            let pieData;

            switch (tipo) {
                case 0:
                    eixoX = [];
                    eixoY = [];
                    pieLabel = [];
                    pieData = [];
                    break;
                case 1:
                    //**Gera Gráfico de linha e dados */
                    eixoY = [];
                    dados_por_data = groupBy(contratos, "data_arquivo");
                    dados_por_data.forEach(dado => {
                        let valor_base_dia = 0;
                        dado.forEach(valor => {
                            valor_base_dia += Number(valor.valor_base.replace(/[^0-9.-]+/g, ""));
                        });
                        eixoY.push(valor_base_dia);
                    });
                    eixoX = [...new Set(contratos.map(contrato => contrato.data_arquivo))];
                    //**Encerra gráficos de linha e dados */

                    /**Gera Gráfico de Pizza e dados */
                    pieData = [];
                    valor_por_produto = groupBy(contratos, "nu_produto");
                    valor_por_produto.forEach(dado => {
                        let valor_base_produto = 0;
                        dado.forEach(valor => {
                            valor_base_produto += Number(valor.valor_base.replace(/[^0-9.-]+/g, ""));
                        });
                        pieData.push(valor_base_produto);
                    });

                    pieLabel = [...new Set(contratos.map(contrato => contrato.nu_produto))];
                    /**Encerra Gráfico de Pizza e dados */

                    break;
            }

            // console.log(eixoX);
            // console.log(eixoY);
            // console.log(pieLabel);
            // console.log(pieData);


            /* Gera Configuração do Gráfico de Linhas */
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
            /* Encerra Configuração do Gráfico de Linhas */

            /* Gera Configuração do Gráfico de Pizza */
            const dataPieChartProdutos = {
                labels: pieLabel,
                datasets: [{
                    label: 'Valor Total por Produto',
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    hoverOffset: 4,
                    data: pieData
                }]
            };

            const configPieChartProdutos = {
                type: 'pie',
                data: dataPieChartProdutos,
            };
            /* Encerra Configuração do Gráfico de Pizza */


            /*Instancia objetos de graficos*/

            var ctx = document.getElementById('myPieChartProdutos');
            console.log(ctx);

            myPieChartProdutos = new Chart(
                ctx,
                configPieChartProdutos
            );

            myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
            /*Encerra objetos de graficos*/
        }

        /**Função para agrupar array de objetos por chave */
        function groupBy(arr, prop) {
            const map = new Map(Array.from(arr, obj => [obj[prop],
                []
            ]));
            arr.forEach(obj => map.get(obj[prop]).push(obj));
            return Array.from(map.values());
        };

        /**Reinicia o Canvas dos Gráficos*/
        var resetarCanvas = function(contratos, tipo) {
            $('#myChart').remove(); // this is my <canvas> element
            $('#corpo-canvas').append('<canvas id="myChart"><canvas>');
            $('#myPieChartProdutos').remove(); // this is my <canvas> element
            $('#corpo-pie').append('<canvas id="myPieChartProdutos"><canvas>');
            geraGrafico(contratos, tipo);
        };
    });
</script>

</html>