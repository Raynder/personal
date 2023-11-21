<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Certificado;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Acesso
 * Table/View: acessos
 *
 * @version September 11, 2023, 14:46 am -03
 *
 * @property int $id
 * @property int $empresa_id
 * @property int $certificado_id
 * @property string $chave
 * @property string $senha
 * @property Carbon\Carbon $data_limite
 * @property string $status
 * @property string $usuario
 * @property string $uuid_usuario
 */
class Acesso extends Model
{
    use BelongsToTenant;

    protected $dates = ['data_limite', 'created_at', 'updated_at'];

    protected $fillable = [
        'empresa_id',
        'usuario_id',
        'certificado_id',
        'chave',
        'senha',
        'data_limite',
        'status',
        'usuario',
        'uuid_usuario',
    ];

    public function certificado(): BelongsTo
    {
        return $this->belongsTo(Certificado::class);
    }

    public function usuarioPc(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}
