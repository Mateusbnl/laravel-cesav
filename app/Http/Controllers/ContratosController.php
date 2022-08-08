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
        $contratos = DB::select('exec ObtemUltimaPosicao');

        $ultima_data = $contratos[0]->data_arquivo;


        $valor_posicao_por_data = DB::select('exec ValorTotalPorData "' . $ultima_data . '", @soma = 0 ');
        $qtd_contratos_por_data = DB::select('exec QtdContratosPorData "' . $ultima_data . '", @qtd = 0 ');

        //formatação de dados para view
        $ultima_data = date('d/m/Y', strtotime($contratos[0]->data_arquivo));

        $fmt = numfmt_create('pt_BR', NumberFormatter::CURRENCY);
        $valor_posicao_por_data = numfmt_format_currency($fmt, $valor_posicao_por_data[0]->total, "BRL");

        return view('index', ['unidades' => $unidades, 'produtos' => $produtos, 'contratos' => $contratos, 'data_posicao' =>  $ultima_data, 'valor_posicao' => $valor_posicao_por_data, 'qtd_contratos' => $qtd_contratos_por_data[0]->qtd]);
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

    public function sinteticoPorUnidade(Request $request)
    {

        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;

        $unidade = $request->unidade;
        $produto = $request->produto;

        $contratos = DB::select('exec SinteticoPorUnidade "' . $data_inicial . '","' . $data_final . '",' . $unidade . ',' . $produto . ',@soma=0, @qtd=0');

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
