<?php

namespace App\Models;

use App\Models\Certificado;
use Illuminate\Database\Eloquent\Model;

class Acesso extends Model
{
    protected $dates = [
        'data_validade',
        'data_limite'
    ];

    protected $fillable = [
        'chave',
        'senha',
        'certificado_id',
        'data_limite',
        'status',
        'usuario',
        'uuid_usuario',
    ];

    public function certificado()
    {
        return $this->belongsTo(Certificado::class);
    }
}
