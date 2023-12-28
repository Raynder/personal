<?php

namespace App\Http\Controllers;

use App\Actions\CriarAcessoCertificadoAction;
use App\Actions\SendChaveMailAction;
use App\Actions\SendLinkUploadByCustomerAction;
use App\Repositories\AlunoRepository;
use App\Helpers\FormatterHelper;
use App\Http\Requests\AlunoRequest;
use App\Models\Acesso;
use App\Models\Aluno;
use App\Models\User;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class AlunoController extends Controller
{
    private $alunoRepository;

    public function __construct(AlunoRepository $alunoRepo)
    {
        $this->alunoRepository = $alunoRepo;
    }

    public function index()
    {
        $alunos = Aluno::where('empresa_id', session()->get('empresa_id'))->get();
        return view('alunos.index', [
            'alunos' => $alunos
        ]);
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        if (isset($searchData['filter_cnpj'])) {
            $searchData['filter_cnpj'] = FormatterHelper::onlyNumbers($searchData['filter_cnpj']);
        }
        $alunos = $this->alunoRepository->all($searchData);

        return view("alunos.table", [
            "alunos" => $alunos,
            "page" => $request->input("page", 0),
            'filter_take' => $request->input('filter_take'),
        ]);
    }

    public function find(Request $request)
    {
        $alunos = $this->alunoRepository->findToSelect2js($request->input("q"));
        return json_encode($alunos);
    }

    public function show($id)
    {
        $aluno = $this->alunoRepository->find($id);

        if (!$aluno) {
            return response()->json("Aluno não encontrado.", 500);
        }

        $acessos = Acesso::where('aluno_id', $aluno->id)->orderBy('usuario')->get();

        return view("alunos.show", ["aluno" => $aluno, 'acessos' => $acessos]);
    }

    public function create()
    {
        return view("alunos.create");
    }

    public function createChave($id)
    {
        $aluno = $this->alunoRepository->find($id);

        if (!$aluno) {
            return response()->json("Registro não encontrado.", 500);
        }
        return view("alunos.createChave", ['aluno' => $aluno]);
    }

    public function compartilharEmLoteForm(Request $request)
    {
        $ids = $request->input('aluno_id');
        if (!$ids) {
            return response()->json("Voc&ecirc; deve selecionar pelo menos um aluno.", 500);
        }
        return view("alunos.compartilharEmLoteForm", ['ids' => $ids]);
    }

    public function store(AlunoRequest $request)
    {
        $input = $request->all();
        $aluno = $this->alunoRepository->create($input);
        if($aluno)
            return response()->json("Salvo com sucesso.", 200);
    }

    public function edit($id)
    {
        $aluno = $this->alunoRepository->find($id);

        if (!$aluno) {
            return response()->json("Registro não encontrado.", 500);
        }
        $usuarios = User::all();
        return view("alunos.edit", ["aluno" => $aluno, "usuarios" => $usuarios]);
    }

    public function update($id, Request $request)
    {
        $alunoModel = $this->alunoRepository->find($id);

        if (!$alunoModel) {
            return response()->json("Registro não encontrado.", 500);
        }

        $input = $request->all();
        $input['empresa_id'] = session()->get('empresa_id');
        $aluno = $this->alunoRepository->update($alunoModel, $input);
        if ($aluno)
            return response()->json("Salvo com sucesso.", 200);
    }

    public function destroy($id)
    {
        $aluno = $this->alunoRepository->find($id);

        if (!$aluno) {
            return response()->json("Registro não encontrado.", 500);
        }
        Acesso::where('aluno_id', $id)->delete();
        $aluno->grupos()->detach();
        $this->alunoRepository->delete($aluno);
        return $id;
    }

    public function aluno($chave)
    {
        if (!$chave) {
            return response()->json("Erro ao gerar chave de acesso.", 500);
        }

        return view("alunos.aluno", ['chave' => $chave]);
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
        $aluno = $this->alunoRepository->find($input['id']);
        if (!$aluno) {
            return response()->json("Aluno não encontrado.", 500);
        }
        $acesso = (new CriarAcessoCertificadoAction())($aluno, $input);

        if (isset($request->mail) && !empty($request->mail)) {
            $mail = $request->mail;

            Mail::send(new SendChaveMailAction($aluno, $mail, $acesso));
            return $acesso;
        }
        return $acesso;
    }

    public function sendTokensByMail(Request $request)
    {
        $input = $request->all();
        $alunos = $this->alunoRepository->findWhereIn($input['aluno_id']);
        if (count($alunos) == 0) {
            return response()->json("Alunos não encontrados.", 500);
        }
        $acesso = (new CriarAcessoCertificadoAction())($alunos->first(), $input);

        if (isset($request->mail) && !empty($request->mail)) {
            $mail = $request->mail;

            Mail::send(new SendChaveMailAction($alunos, $mail, $acesso));
        }
        return $acesso;
    }

    public function sendLinkForm()
    {
        return view("alunos.sendLinkForm");
    }

    public function sendLink(Request $request)
    {
        try {
            $input = $request->all();
            $input['cnpj'] = FormatterHelper::onlyNumbers($input['cnpj']);
            $input['cnpj_raiz'] = substr($input['cnpj'], 0, 8);
            $input['empresa_id'] = session()->get('empresa_id');
            $input['token'] = md5($input['cnpj'] . '-' . time());
            $input['validade_token'] = Carbon::now()->addDay();
            $aluno = $this->alunoRepository->create($input);
            Mail::send(new SendLinkUploadByCustomerAction($request->input('email'), $input['razao_social'], $input['token']));
            return response()->json("Enviado com sucesso.", 200);
        } catch (\Exception $e) {
            return response()->json("Não foi possível salvar. Mensagem: {$e->getMessage()}", 500);
        }
    }

    public function sendTokenForm($id)
    {
        $aluno = $this->alunoRepository->find($id);
        return view("alunos.sendTokenForm", ['aluno' => $aluno]);
    }

    public function sendToken($id, Request $request)
    {
        try {
            $aluno = $this->alunoRepository->find($id);
            $token = md5($aluno->cnpj . '-' . time());
            $aluno->token = $token;
            $aluno->validade_token = Carbon::now()->addDay();
            $aluno->save();
            Mail::send(new SendLinkUploadByCustomerAction($request->input('email'), $aluno->razao_social, $token));
            return response()->json("Link enviado com sucesso.", 200);
        } catch (\Exception $e) {
            return response()->json("Não foi possível salvar. Mensagem: {$e->getMessage()}", 500);
        }
    }

    public function pdf($id)
    {
        $acessos = Acesso::with('aluno')->where('usuario_id', $id)->get();
        $usuario = Usuario::with('empresa')->where('id', $id)->first();

        return view('alunos.pdf', [
            'usuario' => $usuario,
            'acessos' => $acessos
        ]);
    }
}
