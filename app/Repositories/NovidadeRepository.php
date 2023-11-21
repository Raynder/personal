<?php

namespace App\Repositories;

use App\Models\Novidade;
use App\Repositories\AbstractCrudRepository;

class NovidadeRepository extends AbstractCrudRepository
{
    protected $modelClass = Novidade::class;

    public function all($params)
    {
        $qry = $this->newQuery();

        if (isset($params['filter_id'])) {
            $qry = $qry->where('id', '=', $params['filter_id']);
        }
        if (isset($params['filter_razao_social'])) {
            $qry = $qry->where('razao_social', 'ilike', "%{$params['filter_razao_social']}%");
        }

        if (isset($params['filter_cnpj'])) {
            $qry = $qry->where('cnpj', '=', $params['filter_cnpj']);
        }

        if (isset($params['filter_deleted']) && $params['filter_deleted'] == 'S') {
            $qry = $qry->withTrashed();
        }
        if (isset($params['filter_sort'])) {
            $qry = $qry->orderBy($params['filter_sort'], $params['filter_order']);
        }

        return $this->doQuery($qry, $params['filter_take'], true);
    }

    public function findToSelect2js($q)
    {
        $q = strtoupper($q);
        $qry = $this->newQuery();
        $qry = $qry->whereRaw("UPPER(name) ilike '%$q%' ");
        $objetos = $qry->get();
        return $objetos->map(function ($item, $key) {
            return ['id' => $item->id, 'text' => "{$item->name}"];
        });
    }
}
