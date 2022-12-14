<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produto;
use App\Models\Contrato;
use App\Models\Unidade;
use NumberFormatter;
use Illuminate\Support\Facades\Log;

class ContratosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unidades = Unidade::all();
        $produtos = Produto::all();


        $ultima_posicao = DB::select('exec obtemUltimaPosicao ');

        //formatação de dados para view
        $ultima_data = date('d/m/Y', strtotime($ultima_posicao[0]->data_arquivo));
        $valor_posicao = $ultima_posicao[0]->valor_base;
        $qtd_contratos = $ultima_posicao[0]->qtd;

        $fmt = numfmt_create('pt_BR', NumberFormatter::CURRENCY);
        $valor_posicao = numfmt_format_currency($fmt, $valor_posicao, "BRL");

        return view('index', ['unidades' => $unidades, 'produtos' => $produtos, 'data_posicao' =>  $ultima_data, 'valor_posicao' => $valor_posicao, 'qtd_contratos' => $qtd_contratos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        $unidade = $request->unidade;
        $produto = $request->produto;

        $contratos = DB::select('exec ObtemContratos "' . $data_inicial . '","' . $data_final . '",' . $produto . ',' . $unidade . '');
        return response()->json($contratos);
    }

    public function sinteticoTotal(Request $request)
    {
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        $unidade = strval($request->unidade);
        $produto = strval($request->produto);

        if (strcmp("todas_unidades", $unidade) == 0 && strcmp("todos_produtos", $produto) == 0) {
            $contratos = DB::select('exec SinteticoTodos "' . $data_inicial . '","' . $data_final . '", @co_unidade = NULL, @nu_produto = NULL');
        } else if (strcmp("todas_unidades", $unidade) == 0) {
            $contratos = DB::select('exec SinteticoTodos "' . $data_inicial . '","' . $data_final . '", @co_unidade = NULL, @nu_produto = "' . $produto . '"');
        } else if (strcmp("todos_produtos", $produto) == 0) {
            $contratos = DB::select('exec SinteticoTodos "' . $data_inicial . '","' . $data_final . '", @co_unidade = ' . $unidade . ', @nu_produto = NULL');
        } else {
            $contratos = DB::select('exec SinteticoTodos "' . $data_inicial . '","' . $data_final . '", @co_unidade = ' . $unidade . ', @nu_produto = ' . $produto . '');
        }
        return response()->json($contratos);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
