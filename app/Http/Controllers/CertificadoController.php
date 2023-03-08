<?php

namespace App\Http\Controllers;

use App\Actions\CriarAcessoCertificadoAction;
use App\Actions\SendChaveMailAction;
use App\Repositories\CertificadoRepository;
use App\Helpers\FormatterHelper;
use App\Helpers\StringHelper;
use App\Models\Acesso;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class CertificadoController extends Controller
{
    private $certificadoRepository;

    public function __construct(CertificadoRepository $certificadoRepo)
    {
        $this->certificadoRepository = $certificadoRepo;
    }

    public function index()
    {
        return view("certificados.index");
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        if (isset($searchData['filter_cnpj'])) {
            $searchData['filter_cnpj'] = FormatterHelper::onlyNumbers($searchData['filter_cnpj']);
        }
        $certificados = $this->certificadoRepository->all($searchData);

        return view("certificados.table", [
            "certificados" => $certificados,
            "page" => $request->input("page", 0)
        ]);
    }

    public function find(Request $request)
    {
        $certificados = $this->certificadoRepository->findToSelect2js($request->input("q"));
        return json_encode($certificados);
    }

    public function show($id)
    {
        $certificado = $this->certificadoRepository->find($id);

        if (!$certificado) {
            return response()->json("Certificado não encontrado.", 500);
        }

        return view("certificados.show", ["certificado" => $certificado]);
    }

    public function create()
    {
        return view("certificados.create");
    }

    public function createChave($id)
    {
        $certificado = $this->certificadoRepository->find($id);

        if (!$certificado) {
            return response()->json("Registro não encontrado.", 500);
        }
        return view("certificados.createChave", ['certificado' => $certificado]);
    }

    public function store(Request $request)
    {
        $arquivoCertificado = $request->file('certificado');
        if ($arquivoCertificado) {
            $filename = $arquivoCertificado->getClientOriginalName();
            if (!\str_ends_with($filename, '.pfx') && !\str_ends_with($filename, '.p12')) {
                return response()->json('Arquivo Inv&aacute;lido.', 500);
            }
            $certificado = file_get_contents($arquivoCertificado->getRealPath());

            $certs = [];
            $content = openssl_pkcs12_read($certificado, $certs, $request->input('senha'));
            if (!$content) {
                return response()->json('Senha do certificado inv&aacute;lida.', 500);
            }
            $ext = \str_ends_with($filename, '.pfx') ? '.pfx' : '.p12';
            $filename = FormatterHelper::onlyNumbers($request->input('cnpj')) . $ext;
            $path = storage_path('app/certificados/' . $filename);
            file_put_contents($path, $certificado);
            // $base64 = base64_encode($certificado);
            // $ciphertext = openssl_encrypt($base64, 'aes-256-cbc', 'flybi2022', 0, random_bytes(16));
            // $request->merge(['certificado' => $ciphertext]);
        }

        try {
            DB::beginTransaction();
            $input = $request->all();
            $input['cnpj'] = FormatterHelper::onlyNumbers($input['cnpj']);
            $input['cnpj_raiz'] = substr($input['cnpj'], 0, 8);
            $input['empresa_id'] = session()->get('empresa_id');
            $certificado = $this->certificadoRepository->create($input);
            DB::commit();
            return response()->json("Salvo com sucesso.", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("Não foi possível salvar. Mensagem: {$e->getMessage()}", 500);
        }
    }

    public function edit($id)
    {
        $certificado = $this->certificadoRepository->find($id);

        if (!$certificado) {
            return response()->json("Registro não encontrado.", 500);
        }
        $usuarios = User::all();
        return view("certificados.edit", new CertificadoViewModel($usuarios, $certificado));
    }

    public function update($id, Request $request)
    {
        $input = $request->all();
        $certificado = $this->certificadoRepository->find($id);
        if (!$certificado) {
            return response()->json("Registro não encontrado.", 500);
        }

        $certificado = $request->file('certificado');
        if ($certificado) {
            $filename = $certificado->getClientOriginalName();
            if (!\str_ends_with($filename, '.pfx')) {
                return response()->json('Arquivo Inv&aacute;lido.', 500);
            }
            $certificado = file_get_contents($certificado->getRealPath());
            $certs = [];
            if (!openssl_pkcs12_read($certificado, $certs, $request->input('senha_certificado'))) {
                return response()->json('Senha inv&aacute;lida.', 500);
            }
        }
        $input['fone'] = FormatterHelper::onlyNumbers($input['fone']);
        $input['cnpj'] = FormatterHelper::onlyNumbers($input['cnpj']);
        $input['cnpj_raiz'] = substr($input['cnpj'], 0, 8);

        $this->certificadoRepository->update($certificado, $input);
        return response()->json("Salvo com sucesso.", 200);
    }

    public function destroy($id)
    {
        $certificado = $this->certificadoRepository->find($id);

        if (!$certificado) {
            return response()->json("Registro não encontrado.", 500);
        }

        $this->certificadoRepository->delete($certificado);
        return $id;
    }

    public function certificado($chave)
    {
        if (!$chave) {
            return response()->json("Erro ao gerar chave de acesso.", 500);
        }

        return view("certificados.certificado", ['chave' => $chave]);
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
        $certificado = $this->certificadoRepository->find($input['id']);
        if (!$certificado) {
            return response()->json("Certificado não encontrado.", 500);
        }
        $acesso = (new CriarAcessoCertificadoAction())($certificado, $input);

        if(isset($request->mail) && !empty($request->mail)){
            $mail = $request->mail;
            
            Mail::send(new SendChaveMailAction($certificado, $mail, $acesso));
            return $acesso;
        }
        return $acesso;
    }
}
