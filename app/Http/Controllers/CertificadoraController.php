<?php

namespace App\Http\Controllers;

use App\Http\Requests\CertificadoraRequest;
use App\Models\Certificadora;
use App\Repositories\CertificadoraRepository;
use Illuminate\Http\Request;

class CertificadoraController extends Controller
{
    private $certificadoraRepository;

    public function __construct(
        CertificadoraRepository $certificadoraRepo
    ) {
        $this->certificadoraRepository = $certificadoraRepo;
    }

    public function index()
    {
        return view("certificadoras.index");
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        $certificadoras = $this->certificadoraRepository->all($searchData);

        return view("certificadoras.table", [
            "certificadoras" => $certificadoras,
            "page" => $request->input("page", 0),
            "filter_take" => $request->input("filter_take"),
        ]);
    }

    public function find(Request $request)
    {
        $certificadoras = $this->certificadoraRepository->findToSelect2js($request->input("q"));
        return json_encode($certificadoras);
    }

    public function create()
    {
        return view("certificadoras.create", ['certificadora' => new Certificadora()]);
    }

    public function store(CertificadoraRequest $request)
    {
        $input = $request->all();
        $this->certificadoraRepository->create($input);
        return response()->json("Salvo com sucesso.", 200);
    }

    public function show($id)
    {
        $certificadora = $this->certificadoraRepository->find($id);

        if (!$certificadora) {
            return response()->json("Registro não encontrado.", 500);
        }

        return view("certificadoras.show", ["user" => $certificadora]);
    }

    public function edit($id)
    {
        $certificadora = $this->certificadoraRepository->find($id);

        if (!$certificadora) {
            return response()->json("Registro não encontrado.", 500);
        }
        return view("certificadoras.edit", ['certificadora' => $certificadora]);
    }

    public function update($id, CertificadoraRequest $request)
    {
        $certificadora = $this->certificadoraRepository->find($id);

        if (!$certificadora) {
            return response()->json("Registro não encontrado.", 500);
        }

        $this->certificadoraRepository->update($certificadora, $request->all());
        return response()->json("Salvo com sucesso.", 200);
    }

    public function destroy($id)
    {
        $certificadora = $this->certificadoraRepository->find($id);

        if (!$certificadora) {
            return response()->json("Registro não encontrado.", 500);
        }

        $this->certificadoraRepository->delete($certificadora);
        return $id;
    }
}
