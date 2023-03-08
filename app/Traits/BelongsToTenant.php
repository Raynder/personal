<?php

namespace App\Traits;

use App\Models\Empresa;
use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope(new TenantScope);
        static::creating(function ($model) {
            if (session()->has('empresa_id')) {
                $model->empresa_id = session()->get('empresa_id');
            }
        });
    }

    /**
     * @return BelongsTo
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
