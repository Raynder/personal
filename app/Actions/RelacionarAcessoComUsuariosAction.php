<?php

namespace App\Actions;

use App\Models\Acesso;
use App\Models\Certificado;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RelacionarAcessoComUsuariosAction
{

    public function __invoke($acessos, $input)
    {
        // Buscar o usuário que está instalando o certificado
        $usuario = Usuario::where('uuid', $input['uuid_usuario'])->first();
        if (!$usuario) {
            // Na primeira instalação de certificado o usuário deverá ser criado
            Log::info("Usuário com uuid {$input['uuid_usuario']} não encontrado.");
            return;
        } else {
            // Buscar certificados do meu usuário que estão na plataforma
            $cnpjs = $acessos->map(function ($acesso) {
                if(isset($acesso->certificado->cnpj))
                    return $acesso->certificado->cnpj;
            })->toArray();

            // Buscar certificados do meu usuário que foram instalados manualmente
            $certificadosManuais = array_diff($input["certificados"], $cnpjs);
            Log::info("Certificados manuais: " . count($certificadosManuais));

            // Buscar certificados que instalei manualmente e estão na plataforma
            $certificadosExistentes = Certificado::whereIn('cnpj', $certificadosManuais)->get();
            Log::info("Certificados Existentes: " . count($certificadosExistentes));
            // Buscar certificados que instalei manualmente e não existem na plataforma
            $certificadosInexistentes = array_diff($certificadosManuais, $certificadosExistentes->pluck('cnpj')->toArray());
            $acessosExistentes = Acesso::whereIn('chave', $certificadosInexistentes)->pluck('chave')->toArray();
            $certificadosInexistentes = array_diff($certificadosInexistentes, $acessosExistentes);
            Log::info("Certificados inexistentes: " . count($certificadosInexistentes));
            $arrayAcessos = [];
            $item = [];
            $idUsuario =  $usuario->id;
    
            foreach($certificadosExistentes as $certificado) {
                $item['certificado_id'] = $certificado->id;
                $item['empresa_id'] = $usuario->empresa_id;
                $item['usuario_id'] = $idUsuario;
                $item['usuario'] = $usuario->nome;
                $item['uuid_usuario'] = $usuario->uuid;
                $item['status'] = "AA";
                $item['chave'] = Str::random(32);
                $item['created_at'] = Carbon::now();
                $item['updated_at'] = Carbon::now();
                $arrayAcessos[] = $item;
            }

            foreach ($certificadosInexistentes as $certificado) {
                $item['certificado_id'] = null;
                $item['empresa_id'] = $usuario->empresa_id;
                $item['usuario_id'] = $idUsuario;
                $item['usuario'] = $usuario->nome;
                $item['uuid_usuario'] = $usuario->uuid;
                $item['status'] = "AE";
                $item['chave'] = $certificado;
                $item['created_at'] = Carbon::now();
                $item['updated_at'] = Carbon::now();
                $arrayAcessos[] = $item;
            }
            Log::info("Gravando " . count($arrayAcessos) . " certificados");
            Acesso::insert($arrayAcessos);
        }
    }
}
