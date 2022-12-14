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


    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="card p-3 border bg-light" style="width: 51rem; height: 52rem;">
                        <h5 class="card-title">Valor Total por Data</h4>
                            <div class="card-body" id="corpo-canvas">
                                <canvas class="canvas" id="myChart"></canvas>
                            </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-3 border bg-light" style="width: 51rem; height: 52rem;">
                        <h5 class="card-title">Valor Total por Produto</h4>
                            <div class="card-body" id="corpo-pie">
                                <canvas class="canvas" id="myPieChartProdutos"></canvas>
                            </div>
                    </div>
                </div>
            </div>
            <div class="card" id="filtro-tabela">
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
        <div class="col-md-4">
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
            <div class="card p-3 border bg-light">
                <h5 class="card-title">??ltima posi????o: {{ $data_posicao }}</h5>
                <p class="card-text">Quantidade de Contratos: {{$qtd_contratos}}</p>
                <p class="card-footer">Posi????o D??vida Consolidada: {{$valor_posicao}}</p>
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

                    //formata para nosso padr??o de data e moeda
                    ctr = formataData(ctr);



                    //verifica se a op????o todos est?? marcado, pois caso esteja, ira informar sobre a primeira unidade/produto e o usu??rio poder?? ir selecionando as informa????es que deseja
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

        /**Fun????o para gera Gr??ficos conforme dados e tipo de pesquisa */
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
                    //**Gera Gr??fico de linha e dados */
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
                    //**Encerra gr??ficos de linha e dados */

                    /**Gera Gr??fico de Pizza e dados */
                    pieData = [];
                    valor_por_produto = groupBy(contratos, "nu_produto");
                    valor_por_produto.forEach(dado => {
                        let valor_base_produto = 0;
                        dado.forEach(valor => {
                            valor_base_produto += Number(valor.valor_base.replace(/[^0-9.-]+/g, ""));
                        });
                        pieData.push(valor_base_produto.toString());
                    });

                    pieLabel = [...new Set(contratos.map(contrato => contrato.nu_produto))];
                    /**Encerra Gr??fico de Pizza e dados */

                    break;
            }

            // console.log(eixoX);
            // console.log(eixoY);
            // console.log(pieLabel);
            // console.log(pieData);


            /* Gera Configura????o do Gr??fico de Linhas */
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
            /* Encerra Configura????o do Gr??fico de Linhas */

            /* Gera Configura????o do Gr??fico de Pizza */
            const dataPieChartProdutos = {
                labels: pieLabel,
                datasets: [{
                    label: 'Valor Total por Produto',
                    backgroundColor: getRandomColor(pieLabel.length),
                    hoverOffset: 4,
                    data: pieData
                }]
            };

            const configPieChartProdutos = {
                type: 'pie',
                data: dataPieChartProdutos,
            };
            /* Encerra Configura????o do Gr??fico de Pizza */


            /*Instancia objetos de graficos*/

            var ctx = document.getElementById('myPieChartProdutos');

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

        /**Fun????o para agrupar array de objetos por chave */
        function groupBy(arr, prop) {
            const map = new Map(Array.from(arr, obj => [obj[prop],
                []
            ]));
            arr.forEach(obj => map.get(obj[prop]).push(obj));
            return Array.from(map.values());
        };

        //gera cores para os elementos dos graficos
        var colors = [];

        function getRandomColor(qtdProdutos) {
            var colors = [];
            for (var i = 0; i <= qtdProdutos; i++) {
                var letters = '0123456789ABCDEF'.split('');
                var color = '#';
                for (var x = 0; x < 6; x++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                colors.push(color);
            }
            return colors;
        }

        /**Reinicia o Canvas dos Gr??ficos*/
        var resetarCanvas = function(contratos, tipo) {
            $('#myChart').remove();
            $('#corpo-canvas').append('<canvas id="myChart"><canvas>');
            $('#myPieChartProdutos').remove();
            $('#corpo-pie').append('<canvas id="myPieChartProdutos"><canvas>');
            geraGrafico(contratos, tipo);
        };

        /*fun????o para formatar datas pt-br*/
        function formataData(contratos) {
            contratos.forEach(ctr => {
                /** Gambiarra para corre????o em futura itera????o */
                let data = new Date(ctr.data_arquivo);
                data.setDate(data.getDate() + 1);
                /** final da Gambiara - ao transformar a data para formata????o estava indo com um dia a menos, solucionei provisoriamente adicionando um dia depois de transformado - */
                ctr.data_arquivo = data.toLocaleDateString("pt-BR");
            });
            return contratos;
        }
    });
</script>

</html>
