<?php

namespace App\Http\Controllers\External;

use App\Helpers\FormatterHelper;
use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Empresa;
use App\Repositories\AlunoRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

class CustomerController extends Controller
{
    private $certificadoRepository;

    public function __construct(AlunoRepository $certificadoRepo)
    {
        $this->certificadoRepository = $certificadoRepo;
    }

    public function index(Request $request)
    {
        $token = $request->input('t');
        if (!$token) {
            return redirect()->route('external.customer.error')->with('message', 'Token inválido!');
        }
        $certificado = Aluno::where('token', $token)->first();
        if (!$certificado) {
            return redirect()->route('external.customer.error')->with('message', 'O Token informado é inválido!');
        }
        if (!$certificado->tokenValido()) {
            return redirect()->route('external.customer.error')->with('message', 'O Token já expirou! Solicite um novo token.');
        }
        session()->put('eid', $certificado->id);
        session()->put('ename', $certificado->razao_social);
        return view("external.customers.index");
    }

    public function create()
    {
        return view("external.customers.create");
    }

    public function store(Request $request)
    {
        $arquivoAluno = $request->file('certificado');
        if (!$arquivoAluno) {
            return response()->json("Nenhum arquivo enviado.", 500);
        }

        $filename = $arquivoAluno->getClientOriginalName();
        if (!\str_ends_with($filename, '.pfx') && !\str_ends_with($filename, '.p12')) {
            return response()->json('Arquivo Inv&aacute;lido.', 500);
        }
        $certificado = file_get_contents($arquivoAluno->getRealPath());

        $certs = [];
        $content = openssl_pkcs12_read($certificado, $certs, $request->input('senha'));
        if (!$content) {
            return response()->json('Senha do certificado inv&aacute;lida.', 500);
        }
        $certificadoModel = Aluno::find(session()->get('eid'));

        $ext = \str_ends_with($filename, '.pfx') ? '.pfx' : '.p12';
        $filename = FormatterHelper::onlyNumbers($certificadoModel->cnpj) . $ext;
        $path = storage_path('app/certificados/' . $filename);
        file_put_contents($path, $certificado);
        $base64 = base64_encode($certificado);
        $ciphertext = openssl_encrypt($base64, 'aes-256-cbc', 'flybi2022', 0, 'flybisistemas123');
        // $ciphertext = openssl_encrypt($dados, 'aes-256-ctr', 'flybi2022', OPENSSL_RAW_DATA, $iv);

        try {
            DB::beginTransaction();
            $input = $request->all();
            $input['token'] = null;
            $input['validade_token'] = null;
            $input['certificado'] = $ciphertext;
            $certificadoModel = $this->certificadoRepository->update($certificadoModel, $input);
            DB::commit();
            return redirect()->route("external.customer.success");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('external.customer.create')->with('status', 'Houve um problema ao salvar seus dados. ' . $e->getMessage());
        }
    }

    public function error()
    {
        return view('external.customers.error');
    }

    public function success()
    {
        return view('external.customers.success');
    }
}
