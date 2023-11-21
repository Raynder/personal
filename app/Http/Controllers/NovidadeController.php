<?php

namespace App\Http\Controllers;

use App\Actions\CriarAcessoNovidadeAction;
use App\Actions\SendChaveMailAction;
use App\Repositories\NovidadeRepository;
use App\Helpers\FormatterHelper;
use App\Helpers\StringHelper;
use App\Http\Requests\Auth\NovidadeRequest;
use App\Models\Acesso;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class NovidadeController extends Controller
{
    private $novidadeRepository;

    public function __construct(NovidadeRepository $novidadeRepo)
    {
        $this->novidadeRepository = $novidadeRepo;
    }

    public function index()
    {
        return view("novidades.index");
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        if (isset($searchData['filter_titulo'])) {
            $searchData['filter_titulo'] = FormatterHelper::onlyNumbers($searchData['filter_titulo']);
        }
        $novidades = $this->novidadeRepository->all($searchData);

        return view("novidades.table", [
            "novidades" => $novidades,
            "page" => $request->input("page", 0),
            "filter_take" => $request->input("filter_take"),
        ]);
    }

    public function find(Request $request)
    {
        $novidades = $this->novidadeRepository->findToSelect2js($request->input("q"));
        return json_encode($novidades);
    }

    public function show($id)
    {
        $novidade = $this->novidadeRepository->find($id);

        if (!$novidade) {
            return response()->json("Novidade não encontrado.", 500);
        }

        return view("novidades.show", ["novidade" => $novidade]);
    }

    public function create()
    {
        $tipos = [
            1 => 'Nova versão',
            2 => 'Pergunta',
            3 => 'Informação',
        ];

        return view("novidades.create", compact('tipos'));
    }

    public function store(NovidadeRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = auth()->user()->id;
        $novidade = $this->novidadeRepository->create($input);

        if(!$novidade){
            return response()->json("Erro ao salvar registro.", 500);
        }

        return response()->json("Registro salvo com sucesso.", 200);
    }

    public function edit($id)
    {
        $tipos = [
            1 => 'Nova versão',
            2 => 'Pergunta',
            3 => 'Informação',
        ];
        $novidade = $this->novidadeRepository->find($id);

        if (!$novidade) {
            return response()->json("Registro não encontrado.", 500);
        }
        return view("novidades.edit", ["novidade" => $novidade, "tipos" => $tipos]);
    }

    public function update($id, Request $request)
    {
        $novidadeModel = $this->novidadeRepository->find($id);

        if (!$novidadeModel) {
            return response()->json("Registro não encontrado.", 500);
        }

        $novidadeModel->fill($request->all());
        $novidade = $novidadeModel->save();

        if(!$novidade){
            return response()->json("Erro ao atualizar registro.", 500);
        }

        return response()->json("Registro atualizado com sucesso.", 200);
    }

    public function destroy($id)
    {
        $novidade = $this->novidadeRepository->find($id);

        if (!$novidade) {
            return response()->json("Registro não encontrado.", 500);
        }
        $this->novidadeRepository->delete($novidade);
        return $id;
    }

    public function novidade($chave)
    {
        if (!$chave) {
            return response()->json("Erro ao gerar chave de acesso.", 500);
        }

        return view("novidades.novidade", ['chave' => $chave]);
    }

    public function empresa(Request $request, $cnpj)
    {
        if (!$cnpj) {
            return response()->json(["message" => "Nenhum número de documento informado!"], 400);
        }
        if (strlen($cnpj) != 14) {
            return response()->json(["message" => "Número de documento inválido!"], 400);
        }

        $urlApi = "https://flytax.com.br" . "/api/empresas";
        $response = Http::acceptJson()
            ->withHeaders([
                'x-flytax-id' => 1
            ])
            ->get("{$urlApi}/{$cnpj}");

        if (isset($response->json()['id'])) {
            return $response->json();
        }
    }

    public function sendMail(Request $request)
    {
        $input = $request->all();
        $novidade = $this->novidadeRepository->find($input['id']);
        if (!$novidade) {
            return response()->json("Novidade não encontrado.", 500);
        }
        $acesso = (new CriarAcessoNovidadeAction())($novidade, $input);

        if(isset($request->mail) && !empty($request->mail)){
            $mail = $request->mail;
            
            Mail::send(new SendChaveMailAction($novidade, $mail, $acesso));
            return $acesso;
        }
        return $acesso;
    }
}
