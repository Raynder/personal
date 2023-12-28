<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Treino
 * Table/View: treino
 *
 * @version October 09, 2023, 14:27 am -03
 *
 * @property int $id
 * @property int $empresa_id
 * @property string $nome
 * @property string $chave
 */
class Treino extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'empresa_id',
        'nome',
    ];

    public function exercicios()
    {
        return $this->belongsToMany(Usuario::class, 'exercicio_treino', 'treino_id', 'exercicio_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function alunos()
    {
        return $this->belongsToMany(Aluno::class, 'treino_aluno', 'treino_id', 'aluno_id');
    }
}
