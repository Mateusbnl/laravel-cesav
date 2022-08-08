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
    <h1 style="color:green; text-align:center;">
        CESAV - Dashboard
    </h1>
    <div class="container">
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

            <div class="card">
                <div class="card-header">
                    <label for="produto">Unidade</label>
                    <select class="form-select" name="display_unidade" id="display_unidade">
                    </select>

                    <label for="display_produto">Produto</label>
                    <select class="form-select" name="produto" id="display_produto">
                    </select>
                    <br>
                </div>
                <div class="card-body">
                    <table class="table" id="tabela_por_data">
                        <thead>
                            <tr>
                                <th scope="col">Data</th>
                                <th scope="col">Quantidade de Contratos</th>
                                <th scope="col">Valor Base</th>
                            </tr>
                        </thead>
                        <tbody id="corpo">
                        </tbody>
                    </table>
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
    <script src="{{URL::asset('js/tabela-dinamica.js')}}"></script>

</body>

<script type="text/javascript">
    $(document).ready(function() {
        var contratos;
        var url = "{{url('/pesquisar')}}";
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
                    adicionaContratoNaTabela(response, co_unidade, nu_produto);
                },
                error: function(error) {
                    alert('error; ' + eval(error));
                }
            });
        });
    });
</script>

</html>