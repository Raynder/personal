<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Empresa
 * Table/View: empresas
 *
 * @version September 11, 2023, 14:46 am -03
 *
 * @property int $id
 * @property string $cnpj
 * @property string $cnpj_raiz
 * @property string $razao_social
 * @property string $fantasia
 * @property string $telefone
 * @property string $celular
 * @property string $contato
 * @property string $email
 */
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
        'contato',
        'email',
        'client_token',
    ];

    // /**
    //  * @return BelongsToMany
    //  */
    // public function users(): BelongsToMany
    // {
    //     return $this->belongsToMany(User::class, 'empresa_users', 'empresa_id', 'user_id');
    // }

    public function certificados()
    {
        return $this->hasMany(Certificado::class, 'empresa_id');
    }
}
