<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Certificado
 * Table/View: certificados
 *
 * @version September 11, 2023, 14:46 am -03
 *
 * @property int $id
 * @property int $empresa_id
 * @property int $cliente_id
 * @property int $certificadora_id
 * @property string $certificado
 * @property string $senha
 * @property string $num_serie
 * @property Carbon\Carbon $data_validade
 * @property string $nova_senha
 * @property string $criptografia
 * @property string $email
 * @property string $token
 * @property Carbon\Carbon $validade_token
 */
class Certificado extends Model
{
    use HasFactory;
    use BelongsToTenant;

    public $table = 'certificados';

    protected $dates = ['data_validade', 'validade_token'];

    public $fillable = [
        'empresa_id',
        'cliente_id',
        'certificadora_id',
        'cnpj',
        'razao_social',
        'fantasia',
        'senha',
        'novasenha',
        'certificado',
        'num_serie',
        'data_validade',
        'criptografia',
        'email',
        'token',
        'validade_token',
    ];

    protected $casts = [
        'empresa_id' => 'integer',
        'cliente_id' => 'integer',
        'certificadora_id' => 'integer',
        'cnpj' => 'string',
        'razao_social' => 'string',
        'fantasia' => 'string',
        'senha' => 'string',
        'novasenha' => 'string',
        'certificado' => 'string',
        'num_serie' => 'string',
        'email' => 'string',
        'token' => 'string',
    ];

    /**
     * @return HasMany
     */
    public function acessos(): HasMany
    {
        return $this->hasMany(Acesso::class, 'certificado_id');
    }

    /**
     * @return BelongsTo
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_certificado', 'certificado_id', 'grupo_id');
    }

    /**
     * @return BelongsTo
     */
    public function certificadora(): BelongsTo
    {
        return $this->belongsTo(Certificadora::class, 'certificadora_id');
    }

    /**
     * Verifica se o token é valido.
     * Tokens com data anterior ao dia corrente já serão invalidados.
     *
     * @return boolean
     */
    public function tokenValido(): bool
    {
        if (!$this->token || $this->token == '') {
            return false;
        }

        if (!$this->validade_token) {
            return false;
        }

        if ($this->validade_token->isBefore(Carbon::now()->setTime(0, 0))) {
            return false;
        }

        return true;
    }
}
