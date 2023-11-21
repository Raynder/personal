<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function create(Request $request)
    {
        $input = $request->all();
        if (isset($input['nome']) && !empty($input['nome'] && isset($input['uuid_usuario']) && !empty($input['uuid_usuario']) && isset($input['token']) && !empty($input['token']))) {
            $empresa = Empresa::where('client_token', $input['token'])->first();
            $usuario = Usuario::updateOrCreate(
                ['uuid' => $input['uuid_usuario']],
                [
                    'nome' => $input['nome'],
                    'empresa_id' => $empresa->id
                ]
            );
        }
        return response()->json($usuario, 200);
    }
}
