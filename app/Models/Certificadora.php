<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Certificadora
 * Table/View: certificadoras
 *
 * @version September 11, 2023, 14:46 am -03
 *
 * @property int $id
 * @property int $empresa_id
 * @property string $nome
 * @property string $site
 * @property string $telefone
 */
class Certificadora extends Model
{
    use SoftDeletes, HasFactory;
    use BelongsToTenant;

    public $table = 'certificadoras';

    public $fillable = [
        'nome',
        'site',
        'telefone',
        'empresa_id',
    ];

    protected $casts = [
        'nome' => 'string',
        'site' => 'string',
        'telefone' => 'string',
        'empresa_id' => 'integer',
    ];

    /**
     * @return HasMany
     */
    public function certificados(): HasMany
    {
        return $this->hasMany(Certificado::class, 'certificado_id');
    }
}
