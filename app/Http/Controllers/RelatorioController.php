<?php

namespace App\Http\Controllers;

use App\Models\Acesso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{

    public function certificados()
    {
        $acessos = DB::table('acessos')
            ->join('certificados', 'acessos.certificado_id', '=', 'certificados.id', 'left')
            ->join('usuarios', 'acessos.usuario_id', '=', 'exercicios.id', 'left')
            ->join('empresas', 'acessos.empresa_id', '=', 'empresas.id', 'left')
            ->select('acessos.chave', 'acessos.usuario', 'acessos.uuid_usuario', 'acessos.empresa_id', 'acessos.usuario_id', 'acessos.certificado_id', 'certificados.cnpj', 'certificados.razao_social', 'exercicios.nome', 'empresas.fantasia', 'acessos.status')
            ->groupBy('acessos.chave', 'acessos.usuario', 'acessos.uuid_usuario', 'acessos.empresa_id', 'acessos.usuario_id', 'acessos.certificado_id', 'certificados.cnpj', 'certificados.razao_social', 'exercicios.nome', 'empresas.fantasia', 'acessos.status')
            ->get();

        $acessos = $acessos->where('empresa_id', session()->get('empresa_id'));

        return view('relatorios.certificados', [
            'acessos' => $acessos
        ]);
    }
}
