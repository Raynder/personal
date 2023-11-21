<?php

namespace App\Repositories;

use App\Models\Acesso;
use App\Repositories\AbstractCrudRepository;

class AcessoRepository extends AbstractCrudRepository
{
    protected $modelClass = Acesso::class;

    public function all($params)
    {
        $qry = $this->newQuery();

        if (isset($params['filter_id'])) {
            $qry = $qry->where('id', '=', $params['filter_id']);
        }
        if (isset($params['filter_usuario'])) {
            $qry = $qry->where('usuario', 'like', '%' . $params['filter_usuario'] . '%');
        }
        if (isset($params['filter_empresa'])) {
            $qry = $qry->whereHas('certificado', function ($q) use ($params) {
                $q->where('razao_social', 'like', '%' . $params['filter_empresa'] . '%');
            });
        }
        if (isset($params['filter_status'])) {
            $qry = $qry->where('status', '=', $params['filter_status']);
        }
        if (isset($params['filter_deleted']) && $params['filter_deleted'] == 'S') {
            $qry = $qry->withTrashed();
        }
        if (isset($params['filter_sort'])) {
            $qry = $qry->orderBy($params['filter_sort'], $params['filter_order']);
        }

        $qry->whereNotNull('certificado_id')
            ->whereNotIn('status', ['AA', 'AE']);

        return $this->doQuery($qry, $params['filter_take'], true);
    }
}
