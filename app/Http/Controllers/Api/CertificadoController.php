<?php

namespace App\Http\Controllers\Api;

use App\Actions\DeletarCertificadoAction;
use App\Actions\MontarCertificadosPorArquivo;
use App\Actions\MontarCertificadosPorConteudo;
use App\Actions\RelacionarAcessoComUsuariosAction;
use App\Http\Controllers\Controller;
use App\Models\Acesso;
use App\Models\Certificado;
use App\Models\Usuario;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CertificadoController extends Controller
{
    private $hoje;

    public function __construct()
    {
        $this->hoje = Carbon::now();
    }

    // public function index(Request $request)
    // {
    //     $input = $request->all();
    //     Log::info("Iniciando instalação do certificado para o usuário: {$input['usuario']}");
    //     if (isset($input["chave"]) && !empty($input["chave"])) {
    //         $acesso = Acesso::where('chave', $input["chave"])->where('status', 'P')->where('data_limite', '>', $this->hoje)->first();

    //         if (!$acesso) {
    //             Log::info("Chave negada.");
    //             return response()->json(['Chave de acesso negada ou expirada.', 500]);
    //         }

    //         $acesso->status = "A";
    //         $acesso->usuario = $input["usuario"];
    //         $acesso->uuid_usuario = $input["uuid_usuario"];
    //         Log::info("Atualizando informações.");
    //         $acesso->save();

    //         if(!file_exists(storage_path('app/certificados/' . $acesso->certificado->cnpj . '.pfx')) && !file_exists(storage_path('app/certificados/' . $acesso->certificado->cnpj . '.p12')) && !file_exists(storage_path('app/certificados/' . $acesso->certificado->cnpj . '.cer')))
    //         {
    //             Log::info("Certificado não encontrado no servidor.");
    //             return response()->json(['Certificado não encontrado no servidor.', 500]);
    //         }
    //         $ext = ['pfx', 'p12', 'cer'];
    //         foreach ($ext as $e) {
    //             if (file_exists(storage_path('app/certificados/' . $acesso->certificado->cnpj . '.' . $e))) {
    //                 $content = file_get_contents(storage_path('app/certificados/' . $acesso->certificado->cnpj . '.' . $e));
    //                 $acesso->content = base64_encode($content);
    //                 break;
    //             }
    //         }
    //         $acesso->fantasia = $acesso->certificado->fantasia;
    //         $acesso->limite = $acesso->data_limite->format('d/m/Y');

    //         return response()->json($acesso, 200);
    //     }
    // }


    public function retry(Request $request)
    {
        $input = $request->all();
        Log::info("Iniciando instalação do certificado para o usuário: {$input['usuario']}");
        if (isset($input["chave"]) && !empty($input["chave"])) {
            $acessos = Acesso::where('chave', $input["chave"])->where('status', 'A')->where('data_limite', '>', $this->hoje)->get();

            if (count($acessos) == 0) {
                Log::info("Chave negada.");
                return response()->json(['Chave de acesso negada ou expirada.', 500]);
            }

            $certificados = (new MontarCertificadosPorArquivo())($acessos, $input);

            return response()->json($certificados, 200);
        }
    }

    public function download(Request $request)
    {
        $input = $request->all();
        Log::info("Iniciando instalação do certificado para o usuário: {$input['usuario']}");
        if (isset($input["chave"]) && !empty($input["chave"])) {
            $acessos = Acesso::where('chave', $input["chave"])->where('status', 'P')->where('data_limite', '>', $this->hoje)->get();

            if (count($acessos) == 0) {
                $certificado = Certificado::where('novasenha', $input["chave"])->first();
                if ($certificado) {
                    $acesso = Acesso::create([
                        'chave' => $input["chave"],
                        'status' => 'A',
                        'data_limite' => $this->hoje->addDays(90),
                        'certificado_id' => $certificado->id,
                        'empresa_id' => $certificado->empresa_id,
                        'usuario' => $input["usuario"],
                        'uuid_usuario' => $input["uuid_usuario"],
                    ]);
                    $certificados = (new MontarCertificadosPorConteudo())($acesso, $input);
                } else {
                    Log::info("Chave negada.");
                    return response()->json(['Chave de acesso negada ou expirada.', 500]);
                }
            } else {
                $certificados = (new MontarCertificadosPorConteudo())($acessos, $input);
            }

            return response()->json($certificados, 200);
        }
    }

    // Primeira verificação do FlyToken
    public function check(Request $request)
    {
        $input = $request->all();
        if (isset($input["certificados"]) && !empty($input["certificados"])) {
            $input["certificados"] = json_decode($input['certificados']);

            // Buscar certificados do meu uswuário (Pelo meu acesso)
            $acessos = Acesso::whereHas('certificado', function ($query) use ($input) {
                $query->whereIn('cnpj', $input["certificados"])
                    ->where('uuid_usuario', $input["uuid_usuario"]);
                //->whereIn('status', ['PD', 'D']);
            })
                // ->orWhereIn('chave', $input["certificados"])
                // ->where('uuid_usuario', $input["uuid_usuario"])
                ->get();

            (new RelacionarAcessoComUsuariosAction())($acessos, $input);

            $aDesinstalar = $acessos->whereIn('status', ['PD', 'D']);
            $outrosAcessos = Acesso::where('uuid_usuario', $input["uuid_usuario"])
                ->whereIn('status', ['PD', 'D'])
                ->get();

            foreach ($aDesinstalar as $acesso) {
                $acesso->cnpj = $acesso->certificado->cnpj;
            }
            foreach ($outrosAcessos as $acesso) {
                $acesso->cnpj = $acesso->chave;
            }

            $aDesinstalar = array_merge($aDesinstalar->toArray(), $outrosAcessos->toArray());

            return response()->json($aDesinstalar, 200);
        }
    }

    public function uncheck(Request $request)
    {
        $input = $request->all();
        if (isset($input["certificados"]) && !empty($input["certificados"])) {
            $input["certificados"] = json_decode($input['certificados']);

            // Buscar certificados do meu uswuário (Pelo meu acesso)
            $acessos = Acesso::whereHas('certificado', function ($query) use ($input) {
                $query->whereIn('cnpj', $input["certificados"])
                    ->where('uuid_usuario', $input["uuid_usuario"]);
                //->whereIn('status', ['PD']);
            })->get();

            (new RelacionarAcessoComUsuariosAction())($acessos, $input);

            $aDesinstalar = $acessos->where('status', 'PD');

            foreach ($aDesinstalar as $acesso) {
                $aDesinstalar->cnpj = $acesso->certificado->cnpj;
            }
            return response()->json($aDesinstalar, 200);
        }
    }

    // Segunda verificação do FlyToken
    public function active(Request $request)
    {
        $input = $request->all();
        if (isset($input["uuid_usuario"]) && !empty($input["uuid_usuario"])) {
            $usuario = Usuario::where('uuid', $input["uuid_usuario"])->first();
            if (!$usuario) {
                Log::info("Iniciando ativação dos certificado do usuário: {$input['uuid_usuario']}");

                $acessos = Acesso::where('uuid_usuario', $input['uuid_usuario'])
                    ->where('usuario', $input['usuario'])
                    ->whereIn('status', ['I', 'A', 'PA'])
                    ->where('data_limite', '>=', $this->hoje)
                    ->orWhere(
                        function ($query) use ($input) {
                            $query->where('uuid_usuario', $input['uuid_usuario'])
                                ->where('usuario', $input['usuario'])
                                ->where('status', 'I')
                                ->where('data_limite', '<', $this->hoje)
                                ->update(['status' => 'D']);
                        }
                    )
                    ->orWhere(
                        function ($query) use ($input) {
                            $query->where('uuid_usuario', $input['uuid_usuario'])
                                ->where('usuario', $input['usuario'])
                                ->where('status', 'PD')
                                ->update(['status' => 'D']);
                        }
                    )
                    ->distinct('certificado_id')
                    ->get();


                foreach ($acessos as $acesso) {
                    $acesso->cnpj = $acesso->certificado->cnpj;
                    if (strlen($acesso->certificado->certificado) > 1000) {
                        $base64 = openssl_decrypt($acesso->certificado->certificado, 'aes-256-cbc', 'flybi2022', 0, 'flybisistemas123');
                        $acesso->content = $base64;
                    }
                }

                Log::info("Iniciando certificados.");
                return response()->json($acessos, 200);
            } else {
                Log::info("Iniciando ativação dos certificado do usuário: {$usuario->nome}");

                $acessos = Acesso::where('uuid_usuario', $usuario->uuid)
                    ->where('usuario', $usuario->nome)
                    ->whereIn('status', ['I', 'A', 'PA'])
                    ->where('data_limite', '>=', $this->hoje)
                    ->orWhere(
                        function ($query) use ($usuario) {
                            $query->where('uuid_usuario', $usuario->uuid)
                                ->where('usuario', $usuario->nome)
                                ->where('status', 'I')
                                ->where('data_limite', '<', $this->hoje)
                                ->update(['status' => 'D']);
                        }
                    )
                    ->orWhere(
                        function ($query) use ($usuario) {
                            $query->where('uuid_usuario', $usuario->uuid)
                                ->where('usuario', $usuario->nome)
                                ->where('status', 'PD')
                                ->update(['status' => 'D']);
                        }
                    )
                    ->distinct('certificado_id')
                    ->get();

                $grupos = $usuario->grupos;
                $idsCertificadosGrupo = [];
                foreach ($grupos as $grupo) {
                    $idsCertificadosGrupo = array_merge($idsCertificadosGrupo, $grupo->certificados->pluck('id')->toArray());
                }
                // verificar se os ids estão presentes nos acessos retornados, se não estiverem, criar novos acessos e retornar junto com os acessos já existentes.
                $idsCertificadosAcessos = $acessos->pluck('certificado_id')->toArray();
                $idsCertificadosNovos = array_diff($idsCertificadosGrupo, $idsCertificadosAcessos);
                foreach ($idsCertificadosNovos as $id) {
                    $desinstalado = Acesso::where('certificado_id', $id)
                        ->where('status', 'D')
                        ->where('uuid_usuario', $usuario->uuid)
                        ->first();
                    if (!$desinstalado) {
                        $acesso = Acesso::create([
                            'chave' => $usuario->uuid,
                            'status' => 'A',
                            'data_limite' => $this->hoje->addDays(90),
                            'certificado_id' => $id,
                            'empresa_id' => $usuario->empresa_id,
                            'usuario' => $usuario->nome,
                            'usuario_id' => $usuario->id,
                            'uuid_usuario' => $usuario->uuid,
                        ]);
                        $acessos->push($acesso);
                    }
                }

                foreach ($acessos as $acesso) {
                    $montarCertificados = new MontarCertificadosPorConteudo();
                    if ($acesso->certificado->criptografia <= 1) {
                        $acesso = $montarCertificados->cbc($acesso);
                    } else {
                        $acesso = $montarCertificados->ctr($acesso);
                    }
                }

                Log::info("Iniciando certificados.");
                return response()->json($acessos, 200);
            }
        }
    }

    // Funções que alteram o status depois de alguma rotina executada.
    public function statusUninstall(Request $request)
    {
        $input = $request->all();
        if (isset($input["ids"]) && !empty($input["ids"])) {
            $input["ids"] = json_decode($input['ids']);

            $acessos = Acesso::whereIn('id', $input["ids"])->get();

            foreach ($acessos as $acesso) {
                Log::info("Desinstalando acesso {$acesso->certificado->fantasia} do usuário {$acesso->usuario}");
                $certificadoId = $acesso->certificado_id;

                DB::beginTransaction();

                try {
                    $acesso->status = 'D';
                    $acesso->save();

                    // Exclua outros registros com o mesmo certificado_id, exceto o atual
                    $acessosDesinstalado = Acesso::where('certificado_id', $certificadoId)
                        ->where('id', '!=', $acesso->id)
                        // Futuramente trocar para id_usuario
                        ->where('uuid_usuario', $acesso->uuid_usuario)
                        ->get();

                    foreach ($acessosDesinstalado as $ac) {
                        $ac->status = 'D';
                        $ac->save();
                    }

                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error("Erro ao atualizar o status do acesso: " . $e->getMessage());
                }
            }

            return response()->json('Certificados desinstalados', 200);
        }
    }

    public function statusInactive(Request $request)
    {
        $input = $request->all();
        if (isset($input["ids"]) && !empty($input["ids"])) {
            $acessos = Acesso::whereIn('id', $input["ids"])->get();

            foreach ($acessos as $acesso) {
                Log::info("Inativando acesso {$acesso->certificado->fantasia} do usuário {$acesso->usuario}");
                $acesso->status = 'I';
                $acesso->save();
            }

            return response()->json('Certificados desinstalados', 200);
        }
    }

    public function certificadosValidos(Request $request)
    {
        $input = $request->all();
        if (isset($input["ids"]) && !empty($input["ids"])) {
            $certificados = Certificado::whereIn('id', $input["ids"])->get();

            foreach ($certificados as $certificado) {
                $certificado->criptografia = 1;
                (new DeletarCertificadoAction())($certificado);
                $certificado->save();
            }
        }
    }
    public function certificadosInvalidos(Request $request)
    {
        $input = $request->all();
        if (isset($input["ids"]) && !empty($input["ids"])) {
            $certificados = Certificado::whereIn('id', $input["ids"])->get();

            foreach ($certificados as $certificado) {
                $ext = ['pfx', 'p12', 'cer'];
                foreach ($ext as $e) {
                    if (file_exists(storage_path('app/certificados/' . $certificado->cnpj . '.' . $e))) {
                        $content = file_get_contents(storage_path('app/certificados/' . $certificado->cnpj . '.' . $e));
                        $base64 = base64_encode($content);
                        $ciphertext = openssl_encrypt($base64, 'aes-256-ctr', 'flybi2022', 0, 'flybisistemas123');
                        $certificado->certificado = $ciphertext;
                        break;
                    }
                }
                $certificado->criptografia = 2;
                $certificado->save();
            }
        }
    }

    public function statusActive(Request $request)
    {
        $input = $request->all();
        if (isset($input["ids"]) && !empty($input["ids"])) {
            $input["ids"] = json_decode($input['ids']);

            $acessos = Acesso::whereIn('id', $input["ids"])->get();

            foreach ($acessos as $acesso) {
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
            // $certificado->data_validade = date('Y-m-d', strtotime($input["data_validade"]));
            $certificado->data_validade = Carbon::createFromFormat('d/m/Y', $input["validade"]);

            if (isset($input['encriptografia']) && $input['encriptografia']) {
                (new DeletarCertificadoAction())($certificado);
            }
            $certificado->save();
            Log::info("Certificado {$certificado->fantasia} atualizado.");

            return response()->json('Certificado atualizado', 200);
        }
        Log::info("Certificado não encontrado.");
        return response()->json('Certificado não encontrado', 404);
    }

    public function getAllCertificates(Request $request)
    {
        $certificados = Certificado::all();
        // para cada certificado preencher o campo 'content' e o campo 'senha_certificado'
        foreach ($certificados as $certificado) {
            $ext = ['pfx', 'p12', 'cer'];
            foreach ($ext as $e) {
                if (file_exists(storage_path('app/certificados/' . $certificado->cnpj . '.' . $e))) {
                    $content = file_get_contents(storage_path('app/certificados/' . $certificado->cnpj . '.' . $e));
                    if ($content) {
                        $certificado->content = base64_encode($content);
                        $certificado->senha_certificado = $certificado->senha;
                        break;
                    }
                }
            }
        }
        return response()->json($certificados, 200);
    }
}
