<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use SoftDeletes;

    public $table = 'empresas';

    protected $dates = ['deleted_at', 'data_abertura'];

    public $fillable = [
        'cnpj',
        'cnpj_raiz',
        'razao_social',
        'fantasia',
        'telefone',
        'celular',
        'regime_tributario',
        'contato',
        'email'
    ];

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'empresa_users', 'empresa_id', 'user_id');
    }

    public function certificados()
    {
        return $this->hasMany(Certificado::class, 'empresa_id');
    }
}
