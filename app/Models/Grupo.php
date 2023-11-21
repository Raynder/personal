<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Grupo
 * Table/View: grupos
 *
 * @version October 09, 2023, 14:27 am -03
 *
 * @property int $id
 * @property int $empresa_id
 * @property string $nome
 * @property string $chave
 */
class Grupo extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'empresa_id',
        'nome',
    ];

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuario_grupo', 'grupo_id', 'usuario_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function certificados()
    {
        return $this->belongsToMany(Certificado::class, 'grupo_certificado', 'grupo_id', 'certificado_id');
    }
}
