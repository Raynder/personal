<?php

namespace App\Http\Controllers;

use App\Models\Acesso;
use App\Models\Grupo;
use App\Repositories\UsuarioRepository;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    private $usuarioRepository;

    public function __construct(UsuarioRepository $usuarioRepo)
    {
        $this->usuarioRepository = $usuarioRepo;
    }

    public function index()
    {
        $grupos = Grupo::all();
        return view('usuarios.index', [
            'grupos' => $grupos,
        ]);
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        $usuarios = $this->usuarioRepository->all($searchData);
        return view('usuarios.table', [
            'usuarios' => $usuarios,
            "page" => $request->input("page", 0),
            'filter_take' => $request->input('filter_take'),
        ]);
    }

    public function find(Request $request)
    {
        $usuarios = $this->usuarioRepository->findToSelect2js($request->input("q"));
        return json_encode($usuarios);
    }

    public function show($id)
    {
        $acessos = Acesso::where('usuario_id', $id)->get();
        $certificadosFlyToken = $acessos->whereNotNull('certificado_id')->groupBy('certificado_id');
        $outrosCertificados = $acessos->whereNull('certificado_id')->groupBy('chave');

        return view("usuarios.show", [
            'certificadosFlyToken' => $certificadosFlyToken,
            'outrosCertificados' => $outrosCertificados,
            'id' => $id
        ]);
    }
    public function edit($id)
    {
        $usuario = $this->usuarioRepository->find($id);

        if (!$usuario) {
            return response()->json("Usuario não encontrado.", 500);
        }

        return view("usuarios.edit", [
            "usuario" => $usuario,
        ]);
    }

    public function update($id, Request $request)
    {
        $usuario = $this->usuarioRepository->find($id);

        if (!$usuario) {
            return response()->json("Usuario não encontrado.", 500);
        }

        $input = $request->all();
        $this->usuarioRepository->update($usuario, $input);

        if (isset($input['grupos'])) {
            $grupos = $input['grupos'];
            $ids = array_column($grupos, 'grupo_id');
            $usuario->grupos()->sync($ids);
        } else {
            $usuario->grupos()->sync([]);
        }

        return response()->json("Usuario atualizado com sucesso!", 200);
    }

    public function destroy($id)
    {
        $usuario = $this->usuarioRepository->find($id);

        if (!$usuario) {
            return response()->json("Registro não encontrado.", 500);
        }
        // $acessos = $usuario->acessos;
        $usuario->acessos()->delete();
        $grupos = $usuario->grupos;
        $usuario->grupos()->detach($grupos);
        Acesso::where('uuid_usuario', $usuario->uuid)->delete();
        $this->usuarioRepository->delete($usuario);

        return response()->json("Registro excluído com sucesso!", 200);
    }
}
