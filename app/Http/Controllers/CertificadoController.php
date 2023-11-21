<?php

namespace App\Http\Controllers;

use App\Actions\CriarAcessoCertificadoAction;
use App\Actions\SendChaveMailAction;
use App\Actions\SendLinkUploadByCustomerAction;
use App\Repositories\CertificadoRepository;
use App\Helpers\FormatterHelper;
use App\Models\Acesso;
use App\Models\Certificado;
use App\Models\User;
use App\Models\Usuario;
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
        $certificados = Certificado::where('empresa_id', session()->get('empresa_id'))->get();
        return view('certificados.index', [
            'certificados' => $certificados
        ]);
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
            "page" => $request->input("page", 0),
            'filter_take' => $request->input('filter_take'),
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

        $acessos = Acesso::where('certificado_id', $certificado->id)->orderBy('usuario')->get();

        return view("certificados.show", ["certificado" => $certificado, 'acessos' => $acessos]);
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

    public function compartilharEmLoteForm(Request $request)
    {
        $ids = $request->input('certificado_id');
        if (!$ids) {
            return response()->json("Voc&ecirc; deve selecionar pelo menos um certificado.", 500);
        }
        return view("certificados.compartilharEmLoteForm", ['ids' => $ids]);
    }

    public function store(Request $request)
    {
        $arquivoCertificado = $request->file('certificado');
        if (!$arquivoCertificado) {
            return response()->json("Nenhum arquivo enviado.", 500);
        }
        $companyName = '';
        $companyCode = '';
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
            $crt = openssl_x509_parse($certs['cert']);
            $validTo = $crt['validTo_time_t'];
            $expiration_date = date('Y-m-d H:i:s', $validTo);
            $company = array_values(array_filter(explode('/', $crt['name']), fn ($item) => str_contains($item, 'CN=')))[0];
            $company = explode('=', $company)[1];
            $companyName = explode(':', $company)[0];
            $companyCode = explode(':', $company)[1];
            $ext = \str_ends_with($filename, '.pfx') ? '.pfx' : '.p12';
            $filename = FormatterHelper::onlyNumbers($request->input('cnpj')) . $ext;
            $path = storage_path('app/certificados/' . $filename);
            file_put_contents($path, $certificado);
            $base64 = base64_encode($certificado);
            $ciphertext = openssl_encrypt($base64, 'aes-256-cbc', 'flybi2022', 0, 'flybisistemas123');
            // $ciphertext = openssl_encrypt($dados, 'aes-256-ctr', 'flybi2022', OPENSSL_RAW_DATA, $iv);
        }

        try {
            DB::beginTransaction();
            $input = $request->all();
            $input['certificado'] = $ciphertext;
            $input['razao_social'] = $companyName;
            $input['cnpj'] = $companyCode;
            $input['cnpj_raiz'] = substr($input['cnpj'], 0, 8);
            $input['empresa_id'] = session()->get('empresa_id');
            $input['data_validade'] = $expiration_date;
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
        unset($certificado->senha);

        if (!$certificado) {
            return response()->json("Registro não encontrado.", 500);
        }
        $usuarios = User::all();
        return view("certificados.edit", ["certificado" => $certificado, "usuarios" => $usuarios]);
    }

    public function update($id, Request $request)
    {
        $certificadoModel = $this->certificadoRepository->find($id);

        if (!$certificadoModel) {
            return response()->json("Registro não encontrado.", 500);
        }

        $arquivoCertificado = $request->file('certificado');
        if (!$arquivoCertificado) {
            if ($request->has('novasenha')) {
                $this->certificadoRepository->update($certificadoModel, ['novasenha' => $request->input('novasenha')]);
                return response()->json("Nova senha alterada com sucesso.", 200);
            }
            return response()->json("Nenhum arquivo enviado.", 500);
        }
        if ($arquivoCertificado) {
            // limpar o campo data_validade
            $request->merge(['data_validade' => null]);
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
            $base64 = base64_encode($certificado);
            $ciphertext = openssl_encrypt($base64, 'aes-256-cbc', 'flybi2022', 0, random_bytes(16));
        }

        try {
            DB::beginTransaction();
            $input = $request->all();
            $input['certificado'] = $ciphertext;
            $input['cnpj'] = FormatterHelper::onlyNumbers($input['cnpj']);
            $input['cnpj_raiz'] = substr($input['cnpj'], 0, 8);
            $input['empresa_id'] = session()->get('empresa_id');
            $certificado = $this->certificadoRepository->update($certificadoModel, $input);
            DB::commit();
            return response()->json("Salvo com sucesso.", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("Não foi possível salvar. Mensagem: {$e->getMessage()}", 500);
        }
    }

    public function destroy($id)
    {
        $certificado = $this->certificadoRepository->find($id);

        if (!$certificado) {
            return response()->json("Registro não encontrado.", 500);
        }
        Acesso::where('certificado_id', $id)->delete();
        $certificado->grupos()->detach();
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

        if (isset($request->mail) && !empty($request->mail)) {
            $mail = $request->mail;

            Mail::send(new SendChaveMailAction($certificado, $mail, $acesso));
            return $acesso;
        }
        return $acesso;
    }

    public function sendTokensByMail(Request $request)
    {
        $input = $request->all();
        $certificados = $this->certificadoRepository->findWhereIn($input['certificado_id']);
        if (count($certificados) == 0) {
            return response()->json("Certificados não encontrados.", 500);
        }
        $acesso = (new CriarAcessoCertificadoAction())($certificados->first(), $input);

        if (isset($request->mail) && !empty($request->mail)) {
            $mail = $request->mail;

            Mail::send(new SendChaveMailAction($certificados, $mail, $acesso));
        }
        return $acesso;
    }

    public function sendLinkForm()
    {
        return view("certificados.sendLinkForm");
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
            $certificado = $this->certificadoRepository->create($input);
            Mail::send(new SendLinkUploadByCustomerAction($request->input('email'), $input['razao_social'], $input['token']));
            return response()->json("Enviado com sucesso.", 200);
        } catch (\Exception $e) {
            return response()->json("Não foi possível salvar. Mensagem: {$e->getMessage()}", 500);
        }
    }

    public function sendTokenForm($id)
    {
        $certificado = $this->certificadoRepository->find($id);
        return view("certificados.sendTokenForm", ['certificado' => $certificado]);
    }

    public function sendToken($id, Request $request)
    {
        try {
            $certificado = $this->certificadoRepository->find($id);
            $token = md5($certificado->cnpj . '-' . time());
            $certificado->token = $token;
            $certificado->validade_token = Carbon::now()->addDay();
            $certificado->save();
            Mail::send(new SendLinkUploadByCustomerAction($request->input('email'), $certificado->razao_social, $token));
            return response()->json("Link enviado com sucesso.", 200);
        } catch (\Exception $e) {
            return response()->json("Não foi possível salvar. Mensagem: {$e->getMessage()}", 500);
        }
    }

    public function pdf($id)
    {
        $acessos = Acesso::with('certificado')->where('usuario_id', $id)->get();
        $usuario = Usuario::with('empresa')->where('id', $id)->first();

        return view('certificados.pdf', [
            'usuario' => $usuario,
            'acessos' => $acessos
        ]);
    }
}
