<?php

namespace App\Http\Controllers;

use App\Actions\CriarUsuarioMasterAction;
use App\Http\Requests\EmpresaRequest;
use App\Models\Estado;
use App\Repositories\EmpresaRepository;
use App\ViewModels\EmpresaViewModel;
use App\Helpers\FormatterHelper;
use App\Helpers\StringHelper;
use App\Models\Certificado;
use App\Models\Empresa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmpresaController extends Controller
{
    private $empresaRepository;

    public function __construct(EmpresaRepository $empresaRepo)
    {
        $this->empresaRepository = $empresaRepo;
    }

    public function index()
    {
        if (session()->has('empresa_id')) {
            return response()->redirectToRoute('dashboard');
        }
        return view("empresas.index");
    }

    public function search(Request $request)
    {
        $searchData = $request->all();
        $empresas = Empresa::withCount('certificados')->get();

        return view("empresas.table", [
            "empresas" => $empresas,
            "page" => $request->input("page", 0)
        ]);
    }

    public function find(Request $request)
    {
        $empresas = $this->empresaRepository->findToSelect2js($request->input("q"));
        return json_encode($empresas);
    }

    public function show($id)
    {
        $empresa = $this->empresaRepository->find($id);

        if (!$empresa) {
            return response()->json("Registro não encontrado.", 500);
        }

        return view("empresas.show", ["empresa" => $empresa]);
    }

    public function create()
    {
        $usuarios = User::all();
        return view("empresas.create", new EmpresaViewModel($usuarios));
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
            if (!openssl_pkcs12_read($certificado, $certs, $request->input('senha_certificado'))) {
                return response()->json('Senha do certificado inv&aacute;lida.', 500);
            }
            $ext = \str_ends_with($filename, '.pfx') ? '.pfx' : '.p12';
            $filename = FormatterHelper::onlyNumbers($request->input('cnpj')) . $ext;
            $path = storage_path('app/certificados/' . $filename);
            file_put_contents($path, $certificado);
            $request->merge(['certificado' => $path]);
        }

        try {
            DB::beginTransaction();
            $input = $request->all();
            $input['telefone'] = FormatterHelper::onlyNumbers($input['telefone']);
            $input['celular'] = FormatterHelper::onlyNumbers($input['celular']);
            $input['cnpj'] = FormatterHelper::onlyNumbers($input['cnpj']);
            $input['cnpj_raiz'] = substr($input['cnpj'], 0, 8);
            $empresa = $this->empresaRepository->create($input);
            $incluirUsuario = $request->input('incluirMaster') == 'S';
            $senha = $request->input('senha');
            if ($incluirUsuario) {
                // gerar o usuário master
                (new CriarUsuarioMasterAction())($empresa, $senha);
            } else {
                $usuarioExistente = User::find($request->input('usuarioExistenteId'));
                $usuarioExistente->empresas()->attach($empresa);
            }
            DB::commit();
            return response()->json("Salvo com sucesso.", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("Não foi possível salvar. Mensagem: {$e->getMessage()}", 500);
        }
    }

    public function edit($id)
    {
        $empresa = $this->empresaRepository->find($id);

        if (!$empresa) {
            return response()->json("Registro não encontrado.", 500);
        }
        $usuarios = User::all();
        return view("empresas.edit", new EmpresaViewModel($usuarios, $empresa));
    }

    public function update($id, Request $request)
    {
        $input = $request->all();
        $empresa = $this->empresaRepository->find($id);
        if (!$empresa) {
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
        $input['telefone'] = FormatterHelper::onlyNumbers($input['telefone']);
        $input['celular'] = FormatterHelper::onlyNumbers($input['celular']);
        $input['cnpj'] = FormatterHelper::onlyNumbers($input['cnpj']);
        $input['cnpj_raiz'] = substr($input['cnpj'], 0, 8);

        $this->empresaRepository->update($empresa, $input);
        return response()->json("Salvo com sucesso.", 200);
    }

    public function destroy($id)
    {
        $empresa = $this->empresaRepository->find($id);

        if (!$empresa) {
            return response()->json("Registro não encontrado.", 500);
        }

        $this->empresaRepository->delete($empresa);
        return $id;
    }

    public function status($id)
    {
        $empresa = $this->empresaRepository->find($id);

        if (!$empresa) {
            return response()->json("Registro não encontrado.", 500);
        }
        if ($empresa->status_consulta) {
            $empresa->status_consulta = 0;
            $msg = "Empresa desativada com sucesso.";
        } else {
            $empresa->status_consulta = 1;
            $msg = "Empresa ativada com sucesso.";
        }
        $empresa->save();
        return response()->json("{$msg}", 200);
    }

    public function certificado($id)
    {
        $empresa = $this->empresaRepository->find($id);

        if (!$empresa) {
            return response()->json("Registro não encontrado.", 500);
        }
        $chave = Str::random(44);
        $certificado = Certificado::create([
            'empresa_id' => $empresa->id,
            'chave' => $chave,
            'data_validade' => Carbon::now()->addDays(1),
            'senha' => $empresa->senha_certificado,
        ]);

        return view("empresas.certificado", ['certificado' => $certificado]);
    }
}
