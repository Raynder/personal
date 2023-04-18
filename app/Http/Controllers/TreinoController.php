<?php

namespace App\Http\Controllers;

use App\Models\Treino;
use Illuminate\Http\Request;
use App\Repositories\TreinoRepository;

class TreinoController extends Controller
{

    private TreinoRepository $treinoRepository;

    public function __construct(TreinoRepository $treinoRepository)
    {
        $this->treinoRepository = $treinoRepository;
    }

    public function index()
    {
        return view('treinos.index');
    }

    public function create()
    {
        return view('treinos.create');
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        $treinos = $this->treinoRepository->all($searchData);

        return view('treinos.table', [
            'treinos' => $treinos,
            'searchData' => $searchData
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $this->treinoRepository->create($data);

        return response()->json("Registro salvo com sucesso.", 200);
    }

    public function show($id)
    {
        $treino = $this->treinoRepository->find($id);
        if(!$treino){
            return response()->json("Registro não encontrado.", 500);
        }
        return view('treinos.show', compact('treino'));
    }

    public function edit($id)
    {
        $treino = $this->treinoRepository->find($id);
        if(!$treino){
            return response()->json("Registro não encontrado.", 500);
        }
        return view('treinos.edit', compact('treino'));
    }

    public function update(Request $request, $id)
    {
        $treinoModel = $this->treinoRepository->find($id);
        if(!$treinoModel){
            return response()->json("Registro não encontrado.", 500);
        }
        $data = $request->all();
        $this->treinoRepository->update($treinoModel, $data);

        return response()->json("Registro atualizado com sucesso.", 200);
    }

    public function destroy($id)
    {
        $treino = $this->treinoRepository->find($id);

        if(!$treino){
            return response()->json("Registro não encontrado.", 500);
        }

        $this->treinoRepository->delete($treino);
        return response()->json("Registro excluído com sucesso.", 200);
    }
}
