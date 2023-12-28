<?php

namespace App\Http\Controllers;

use App\Models\Acesso;
use App\Models\Treino;
use App\Repositories\ExercicioRepository;
use Illuminate\Http\Request;

class ExercicioController extends Controller
{
    private $usuarioRepository;

    public function __construct(ExercicioRepository $usuarioRepo)
    {
        $this->usuarioRepository = $usuarioRepo;
    }

    public function index()
    {
        $treinos = Treino::all();
        return view('exercicios.index', [
            'treinos' => $treinos,
        ]);
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        $usuarios = $this->usuarioRepository->all($searchData);
        return view('exercicios.table', [
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

    public function create()
    {
        return view("exercicios.create", [
            "exercicio" => new Treino(),
        ]);
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

        if (isset($input['treinos'])) {
            $treinos = $input['treinos'];
            $ids = array_column($treinos, 'grupo_id');
            $usuario->treinos()->sync($ids);
        } else {
            $usuario->treinos()->sync([]);
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
        $treinos = $usuario->treinos;
        $usuario->treinos()->detach($treinos);
        Acesso::where('uuid_usuario', $usuario->uuid)->delete();
        $this->usuarioRepository->delete($usuario);

        return response()->json("Registro excluído com sucesso!", 200);
    }
}
