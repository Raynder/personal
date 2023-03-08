<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificado extends Model
{
    use SoftDeletes;
    use BelongsToTenant;

    public $table = 'certificados';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'empresa_id',
        'cnpj',
        'razao_social',
        'fantasia',
        'senha',
        'certificado',
        'num_serie',
        'validade'
    ];
}
