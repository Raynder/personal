<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 * Table/View: users
 *
 * @version September 11, 2023, 14:46 am -03
 *
 * @property int $id
 * @property int $empresa_id
 * @property string $name
 * @property string $email
 * @property string $type F=Funcionario | C=Cliente
 * @property string $password
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use BelongsToTenant;

    protected $fillable = [
        'name',
        'email',
        'password',
        'empresa_id',
        'type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return BelongsToMany
     */
    public function empresas(): BelongsToMany
    {
        return $this->belongsToMany(Empresa::class, 'empresa_users', 'user_id', 'empresa_id');
    }
}
