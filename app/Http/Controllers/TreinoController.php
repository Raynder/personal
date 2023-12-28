<?php

namespace App\Http\Controllers;

use App\Models\Acesso;
use App\Models\Treino;
use App\Models\Usuario;
use App\Repositories\TreinoRepository;
use App\Repositories\ExercicioRepository;
use Illuminate\Http\Request;

class TreinoController extends Controller
{
    private $treinoRepository;
    private $usuarioRepository;

    public function __construct(TreinoRepository $treinoRepo, ExercicioRepository $usuarioRepo)
    {
        $this->usuarioRepository = $usuarioRepo;
        $this->treinoRepository = $treinoRepo;
    }

    public function index()
    {
        return view('treinos.index');
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        $treinos = $this->treinoRepository->all($searchData);
        return view('treinos.table', [
            'treinos' => $treinos,
            "page" => $request->input("page", 0),
            'filter_take' => $request->input('filter_take'),
        ]);
    }

    public function create()
    {
        $usuarios = Usuario::orderBy('nome')->get();
        return view("treinos.create", [
            "treino" => new Treino(),
            "usuarios" => $usuarios,
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $treino = $this->treinoRepository->create($input);

        if (!$treino) {
            return response()->json("Erro ao salvar registro.", 500);
        }

        if (isset($input['alunos'])) {
            $alunos = $input['alunos'];
            $ids = array_column($alunos, 'aluno_id');
            $treino->alunos()->sync($ids);
        }

        if (isset($input['usuario_id'])) {
            $usuarios = $input['usuario_id'];
            $treino->usuarios()->sync($usuarios);
        }

        return response()->json("Registro salvo com sucesso.", 200);
    }

    public function find(Request $request)
    {
        $treinos = $this->treinoRepository->findToSelect2js($request->input("q"));
        return json_encode($treinos);
    }

    public function show($id)
    {
        $acessos = Acesso::where('usuario_id', $id)->get();
        $alunosFlyToken = $acessos->whereNotNull('aluno_id')->groupBy('aluno_id');
        $outrosCertificados = $acessos->whereNull('aluno_id')->groupBy('chave');

        return view("usuarios.show", [
            'alunosFlyToken' => $alunosFlyToken,
            'outrosCertificados' => $outrosCertificados,
            'id' => $id
        ]);
    }
    public function edit($id)
    {
        $treino = $this->treinoRepository->find($id);

        if (!$treino) {
            return response()->json("Usuario não encontrado.", 500);
        }

        $usuarios = Usuario::orderBy('nome')->get();
        return view("treinos.edit", [
            "treino" => $treino,
            "usuarios" => $usuarios,
        ]);
    }

    public function update($id, Request $request)
    {
        $treino = $this->treinoRepository->find($id);

        if (!$treino) {
            return response()->json("Usuario não encontrado.", 500);
        }

        $input = $request->all();
        $this->treinoRepository->update($treino, $input);

        if (isset($input['alunos'])) {
            $alunos = $input['alunos'];
            $ids = array_column($alunos, 'aluno_id');
            $treino->alunos()->sync($ids);
        }

        if (isset($input['usuario_id'])) {
            $usuarios = $input['usuario_id'];
            $treino->usuarios()->sync($usuarios);
        }

        return response()->json("Usuario atualizado com sucesso!", 200);
    }

    public function destroy($id)
    {
        $treino = $this->treinoRepository->find($id);

        if (!$treino) {
            return response()->json("Registro não encontrado.", 500);
        }
        $alunos = $treino->alunos;
        $treino->alunos()->detach($alunos);
        $usuarios = $treino->usuarios;
        $treino->usuarios()->detach($usuarios);
        $this->treinoRepository->delete($treino);

        return response()->json("Registro excluído com sucesso!", 200);
    }
}
