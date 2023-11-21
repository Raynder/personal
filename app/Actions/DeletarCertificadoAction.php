<?php

namespace App\Actions;

use App\Models\Certificado;

class DeletarCertificadoAction
{
    /**
     * Deletar arquivo fisico do certificado
     *
     * @param Certificado $certificado
     */
    public function __invoke(Certificado $certificado): void
    {
        $ext = ['pfx', 'p12', 'cer'];
        foreach ($ext as $e) {
            if(file_exists(storage_path('app/certificados/' . $certificado->cnpj . '.' . $e))){
                unlink(storage_path('app/certificados/' . $certificado->cnpj . '.' . $e));
            }
        }
    }
    
}
