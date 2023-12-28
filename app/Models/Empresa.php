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
 * @property string $razao_social
 * @property string $fantasia
 * @property string $email
 */
class Empresa extends Model
{
    use SoftDeletes;
    public $table = 'empresas';

    protected $dates = ['deleted_at', 'data_abertura'];

    public $fillable = [
        'razao_social',
        'fantasia',
        'email',
    ];

    // /**
    //  * @return BelongsToMany
    //  */
    // public function users(): BelongsToMany
    // {
    //     return $this->belongsToMany(User::class, 'empresa_users', 'empresa_id', 'user_id');
    // }

    public function alunos()
    {
        return $this->hasMany(Aluno::class, 'empresa_id');
    }
}
