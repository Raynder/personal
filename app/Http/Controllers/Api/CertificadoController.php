<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Acesso;
use App\Models\Certificado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CertificadoController extends Controller
{
    private $hoje;

    public function __construct()
    {
        $this->hoje = Carbon::now();
    }

    public function index(Request $request)
    {
        $input = $request->all();
        Log::info("Iniciando instalação do certificado para o usuário: {$input['usuario']}");
        if (isset($input["chave"]) && !empty($input["chave"])) {
            $acesso = Acesso::where('chave', $input["chave"])->where('status', 'P')->where('data_limite', '>', $this->hoje)->first();

            if (!$acesso) {
                Log::info("Chave negada.");
                return response()->json(['Chave de acesso negada ou expirada.', 500]);
            }

            $acesso->status = "A";
            $acesso->usuario = $input["usuario"];
            $acesso->uuid_usuario = $input["uuid_usuario"];
            Log::info("Atualizando informações.");
            $acesso->save();

            $content = file_get_contents(storage_path('app/certificados/' . $acesso->certificado->cnpj . '.pfx'));
            $acesso->fantasia = $acesso->certificado->fantasia;
            $acesso->limite = $acesso->data_limite->format('d/m/Y');
            $acesso->content = base64_encode($content);

            return response()->json($acesso, 200);
        }
    }

    public function check(Request $request)
    {
        $input = $request->all();
        if (isset($input["certificados"]) && !empty($input["certificados"])) {
            $input["certificados"] = json_decode($input['certificados']);

            $acessos = Acesso::whereHas('certificado', function ($query) use ($input) {
                $query->whereIn('cnpj', $input["certificados"])->where('uuid_usuario', $input["uuid_usuario"])->whereIn('status', ['PD', 'D']);
            })->get();

            Log::info($acessos);

            foreach ($acessos as $acesso) {
                $acessos->cnpj = $acesso->certificado->cnpj;
            }

            Log::info("Verificando acessos.");
            return response()->json($acessos, 200);
        }
    }

    public function uncheck(Request $request)
    {
        $input = $request->all();
        if (isset($input["certificados"]) && !empty($input["certificados"])) {
            $input["certificados"] = json_decode($input['certificados']);

            $acessos = Acesso::whereHas('certificado', function ($query) use ($input) {
                $query->whereIn('cnpj', $input["certificados"])->where('uuid_usuario', $input["uuid_usuario"])->whereIn('status', ['PD']);
            })->get();

            
            Log::info($acessos);
            
            foreach ($acessos as $acesso) {
                $acessos->cnpj = $acesso->certificado->cnpj;
            }

            Log::info("Verificando acessos.");
            return response()->json($acessos, 200);
        }
    }

    public function active(Request $request)
    {
        $input = $request->all();
        Log::info($input);
        if (isset($input["uuid_usuario"]) && !empty($input["uuid_usuario"])) {
            Log::info("Iniciando ativação dos certificado do usuário: {$input['uuid_usuario']}");

            $acessos = Acesso::where('uuid_usuario', $input['uuid_usuario'])
                ->whereIn('status', ['I','A'])
                ->where('data_limite', '>=', $this->hoje)
                ->orWhere(
                    function ($query) use ($input) {
                    $query->where('uuid_usuario', $input['uuid_usuario'])
                            ->where('status', 'I')
                            ->where('data_limite', '<', $this->hoje)
                            ->update(['status' => 'D']);
                        })
                ->orWhere(
                    function ($query) use ($input) {
                    $query->where('uuid_usuario', $input['uuid_usuario'])
                            ->where('status', 'PD')
                            ->update(['status' => 'D']);
                        })
                ->get();

            Log::info($acessos);

            foreach ($acessos as $acesso) {
                Log::info('certificado');
                Log::info($acesso->certificado);
                $acesso->cnpj = $acesso->certificado->cnpj;
                $content = file_get_contents(storage_path('app/certificados/' . $acesso->cnpj . '.pfx'));
                $acesso->content = base64_encode($content);
            }

            Log::info("Iniciando certificados.");
            return response()->json($acessos, 200);
        }
    }

    // Funções que alteram o status depois de alguma rotina executada.
    public function statusUninstall(Request $request)
    {
        $input = $request->all();
        if (isset($input["ids"]) && !empty($input["ids"])) {
            $input["ids"] = json_decode($input['ids']);

            $acessos = Acesso::whereIn('id', $input["ids"])->get();

            foreach($acessos as $acesso)
            {
                Log::info("Desinstalando acesso {$acesso->certificado->fantasia} do usuário {$acesso->usuario}");
                $acesso->status = 'D';
                $acesso->save();
            }

            return response()->json('Certificados desinstalados', 200);
        }
    }

    public function statusInactive(Request $request)
    {
        $input = $request->all();
        if (isset($input["ids"]) && !empty($input["ids"])) {
            $input["ids"] = json_decode($input['ids']);

            $acessos = Acesso::whereIn('id', $input["ids"])->get();

            foreach($acessos as $acesso)
            {
                Log::info("Inativando acesso {$acesso->certificado->fantasia} do usuário {$acesso->usuario}");
                $acesso->status = 'I';
                $acesso->save();
            }

            return response()->json('Certificados desinstalados', 200);
        }
    }

    public function statusActive(Request $request)
    {
        $input = $request->all();
        if (isset($input["ids"]) && !empty($input["ids"])) {
            $input["ids"] = json_decode($input['ids']);

            $acessos = Acesso::whereIn('id', $input["ids"])->get();

            foreach($acessos as $acesso)
            {
                Log::info("Ativando acesso {$acesso->certificado->fantasia} do usuário {$acesso->usuario}");
                $acesso->status = 'A';
                $acesso->save();
            }

            return response()->json('Certificados instalados', 200);
        }
    }

    public function updateCertificate(Request $request)
    {
        $input = $request->all();
        if (isset($input["certificado_id"]) && !empty($input["certificado_id"])) {
            $certificado = Certificado::find($input["certificado_id"]);
            $certificado->num_serie = $input["num_serie"];
            $certificado->validade = $input["validade"];
            $certificado->save();
            Log::info("Certificado {$certificado->fantasia} atualizado.");

            return response()->json('Certificado atualizado', 200);
        }
        Log::info("Certificado não encontrado.");
        return response()->json('Certificado não encontrado', 404);
    }
}
