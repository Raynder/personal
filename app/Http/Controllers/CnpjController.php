<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CnpjController extends Controller
{
    public function index(Request $request)
    {
        $cnpj = preg_replace("/\D/", '', $request->input('cnpj'));
        $urlApi = 'https://flytax.com.br/api/empresas';
        $response = Http::acceptJson()
            ->withHeaders([
                'x-flyprice-id' => 1,
            ])
            ->get("{$urlApi}/{$cnpj}");
        if (!$response->json('razao_social')) {
            return response()->json('Não foi possível buscar a empresa pelo CNPJ. Cadastre os dados manualmente.', 500);
        }
        $dados = [];
        $dados['cnpj'] = $cnpj;
        $dados['nome'] = $response->json('razao_social');
        $dados['razao_social'] = $response->json('razao_social');
        $dados['fantasia'] = $response->json('fantasia');
        $dados['logradouro'] = $response->json('logradouro');
        $dados['bairro'] = $response->json('bairro');
        $dados['cidade'] = $response->json('cidade');
        $dados['estado'] = $response->json('estado');
        $dados['cep'] = $response->json('cep');


        return response()->json($dados);
    }
}
