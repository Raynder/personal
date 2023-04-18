<?php

namespace App\Http\Controllers;

use App\Models\Exercicio;
use Illuminate\Http\Request;
use App\Repositories\ExercicioRepository;

class ExercicioController extends Controller
{

    private ExercicioRepository $exercicioRepository;

    public function __construct(ExercicioRepository $exercicioRepository)
    {
        $this->exercicioRepository = $exercicioRepository;
    }

    public function index()
    {
        return view('exercicios.index');
    }

    public function create()
    {
        return view('exercicios.create');
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        $exercicios = $this->exercicioRepository->all($searchData);

        return view('exercicios.table', [
            'exercicios' => $exercicios,
            'searchData' => $searchData
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $this->exercicioRepository->create($data);

        return response()->json("Registro salvo com sucesso.", 200);
    }

    public function show($id)
    {
        $exercicio = $this->exercicioRepository->find($id);
        if(!$exercicio){
            return response()->json("Registro não encontrado.", 500);
        }
        return view('exercicios.show', compact('exercicio'));
    }

    public function edit($id)
    {
        $exercicio = $this->exercicioRepository->find($id);
        if(!$exercicio){
            return response()->json("Registro não encontrado.", 500);
        }
        return view('exercicios.edit', compact('exercicio'));
    }

    public function update(Request $request, $id)
    {
        $exercicioModel = $this->exercicioRepository->find($id);
        if(!$exercicioModel){
            return response()->json("Registro não encontrado.", 500);
        }
        $data = $request->all();
        $this->exercicioRepository->update($exercicioModel, $data);

        return response()->json("Registro atualizado com sucesso.", 200);
    }

    public function destroy($id)
    {
        $exercicio = $this->exercicioRepository->find($id);

        if(!$exercicio){
            return response()->json("Registro não encontrado.", 500);
        }

        $this->exercicioRepository->delete($exercicio);
        return response()->json("Registro excluído com sucesso.", 200);
    }
}
