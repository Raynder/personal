<?php

namespace App\Http\Controllers;

// use App\Repositories\AcessoRepository;

use App\Repositories\AcessoRepository;
use Illuminate\Http\Request;

class AcessoController extends Controller
{
    private $acessoRepository;

    public function __construct(AcessoRepository $acessoRepo)
    {
        $this->acessoRepository = $acessoRepo;
    }

    public function index()
    {
        return view("acessos.index");
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        $acessos = $this->acessoRepository->all($searchData);

        return view("acessos.table", [
            "acessos" => $acessos,
            "page" => $request->input("page", 0)
        ]);
    }

    public function show($id)
    {
        $acesso = $this->acessoRepository->find($id);

        if (!$acesso) {
            return response()->json("Acesso não encontrado.", 500);
        }

        return view("acessos.show", ["acesso" => $acesso]);
    }


    public function create()
    {
        return view("acessos.create");
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $acesso = $this->acessoRepository->create($input);

        return response()->json("Acesso criado com sucesso!", 200);
    }

    public function destroy($id)
    {
        $acesso = $this->acessoRepository->find($id);

        if (!$acesso) {
            return response()->json("Acesso não encontrado.", 500);
        }

        $this->acessoRepository->delete($acesso);

        return response()->json("Acesso excluído com sucesso!", 200);
    }

    public function edit($id)
    {
        $acesso = $this->acessoRepository->find($id);

        if (!$acesso) {
            return response()->json("Acesso não encontrado.", 500);
        }

        return view("acessos.acoes", ["acesso" => $acesso]);
    }

    public function update($id, Request $request)
    {
        $acesso = $this->acessoRepository->find($id);

        if (!$acesso) {
            return response()->json("Acesso não encontrado.", 500);
        }

        $input = $request->all();
        $this->acessoRepository->update($acesso, $input);

        return response()->json("Acesso atualizado com sucesso!", 200);
    }

    public function status($id)
    {
        $acesso = $this->acessoRepository->find($id);

        if (!$acesso) {
            return response()->json("Acesso não encontrado.", 500);
        }
        if ($acesso->status == "A" || $acesso->status == "I") {
            $acesso->status = "PD";
            $acesso->save();
            $msg = "Desinstalação iniciada com sucesso.";
        }
        if ($acesso->status == "PD") {
            $msg = "O certificado ainda não foi desinstalado do maquina do usuário.";
        }
        if ($acesso->status == "D") {
            $msg = "Acesso finalizado.";
        }
        return response()->json("{$msg}", 200);
    }

    public function opcoes($id)
    {
        $acesso = $this->acessoRepository->find($id);
        return view("acessos.acoes", ['acesso' => $acesso]);
    }

    public function acoes(Request $request)
    {
        $input = $request->all();
        $acesso = $this->acessoRepository->find($input['id']);
        if($input['status'] == 'E')
        {
            $this->acessoRepository->delete($acesso);
            return response()->json("Acesso excluído com sucesso!", 200);
        }
        else
        {
            $this->acessoRepository->update($acesso, $input);
            return response()->json("Atualização iniciada com sucesso!", 200);
        }
    }
}
