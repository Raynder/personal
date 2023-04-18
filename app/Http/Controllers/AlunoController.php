<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;
use App\Repositories\AlunoRepository;

class AlunoController extends Controller
{

    private AlunoRepository $alunoRepository;

    public function __construct(AlunoRepository $alunoRepository)
    {
        $this->alunoRepository = $alunoRepository;
    }

    public function index()
    {
        return view('alunos.index');
    }

    public function create()
    {
        return view('alunos.create');
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        $alunos = $this->alunoRepository->all($searchData);

        return view('alunos.table', [
            'alunos' => $alunos,
            'searchData' => $searchData
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $this->alunoRepository->create($data);

        return response()->json("Registro salvo com sucesso.", 200);
    }

    public function show($id)
    {
        $aluno = $this->alunoRepository->find($id);
        if(!$aluno){
            return response()->json("Registro não encontrado.", 500);
        }
        return view('alunos.show', compact('aluno'));
    }

    public function edit($id)
    {
        $aluno = $this->alunoRepository->find($id);
        if(!$aluno){
            return response()->json("Registro não encontrado.", 500);
        }
        return view('alunos.edit', compact('aluno'));
    }

    public function update(Request $request, $id)
    {
        $alunoModel = $this->alunoRepository->find($id);
        if(!$alunoModel){
            return response()->json("Registro não encontrado.", 500);
        }
        $data = $request->all();
        $this->alunoRepository->update($alunoModel, $data);

        return response()->json("Registro atualizado com sucesso.", 200);
    }

    public function destroy($id)
    {
        $aluno = $this->alunoRepository->find($id);

        if(!$aluno){
            return response()->json("Registro não encontrado.", 500);
        }

        $this->alunoRepository->delete($aluno);
        return response()->json("Registro excluído com sucesso.", 200);
    }
}
