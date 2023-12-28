<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Usuario
 * Table/View: usuarios
 *
 * @version October 09, 2023, 12:54 am -03
 *
 * @property int $id
 * @property int $empresa_id
 * @property string $nome
 * @property string $uuid
 */
class Exercicio extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'empresa_id',
        'nome',
        'apelido',
        'uuid',
    ];

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'usuario_grupo', 'usuario_id', 'grupo_id');
    }

    public function acessos()
    {
        return $this->hasMany(Acesso::class, 'usuario_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
