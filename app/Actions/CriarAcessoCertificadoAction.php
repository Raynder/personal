<?php

namespace App\Actions;

use App\Models\Acesso;
use App\Models\Certificado;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CriarAcessoCertificadoAction
{

    public function __invoke(Certificado $certificado, $input = null)
    {
        $chave = Str::random(44);
        if(isset($input['certificado_id'])) {
            foreach ($input['certificado_id'] as $id) {
                $acesso = Acesso::create([
                    'certificado_id' => $id,
                    'chave' => $chave,
                    'data_limite' => isset($input['data_limite']) ? $input['data_limite'] : Carbon::now()->addDays(365),
                ]);
            }
        }
        else{
            $acesso = Acesso::create([
                'certificado_id' => $certificado->id,
                'chave' => $chave,
                'data_limite' => isset($input['data_limite']) ? $input['data_limite'] : Carbon::now()->addDays(365),
            ]);
        }


        return $acesso;
    }
}