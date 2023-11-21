<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Cliente
 * Table/View: clientes
 *
 * @version September 11, 2023, 14:46 am -03
 *
 * @property int $id
 * @property int $empresa_id
 * @property int $user_id
 * @property string $cnpj
 * @property string $razao_social
 * @property string $fantasia
 * @property string $email
 */
class Cliente extends Model
{
    use SoftDeletes, HasFactory;
    use BelongsToTenant;

    public $table = 'clientes';

    public $fillable = [
        'cnpj',
        'razao_social',
        'fantasia',
        'email',
        'empresa_id',
        'user_id',
    ];

    protected $casts = [
        'cnpj' => 'string',
        'razao_social' => 'string',
        'fantasia' => 'string',
        'email' => 'string',
        'empresa_id' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
