<?php

namespace App\Repositories;

use App\Models\Exercicio;
use App\Repositories\AbstractCrudRepository;

class ExercicioRepository extends AbstractCrudRepository
{
    protected $modelClass = Exercicio::class;

    public function all($params)
    {
        $qry = $this->newQuery();

        if (isset($params['filter_id'])) {
            $qry = $qry->where('id', '=', $params['filter_id']);
        }
        if (isset($params['filter_grupo'])) {
            $qry = $qry->whereHas('grupos', function ($query) use ($params) {
                $query->where('grupo_id', '=', $params['filter_grupo']);
            });
        }
        if (isset($params['filter_nome'])) {
            $qry = $qry->where('nome', 'ilike', "%{$params['filter_nome']}%");
        }

        if (isset($params['filter_deleted']) && $params['filter_deleted'] == 'S') {
            $qry = $qry->withTrashed();
        }
        if (isset($params['filter_sort'])) {
            $qry = $qry->orderBy($params['filter_sort'], $params['filter_order']);
        }

        $qry->where('empresa_id', session()->get('empresa_id'));

        return $this->doQuery($qry, $params['filter_take'], true);
    }

    public function findToSelect2js($q)
    {
        $q = strtoupper($q);
        $qry = $this->newQuery();
        $qry = $qry->whereRaw("UPPER(nome) ilike '%$q%' ");
        $objetos = $qry->get();
        return $objetos->map(function ($item, $key) {
            return ['id' => $item->id, 'text' => "{$item->nome}"];
        });
    }

    public function findWhereIn($ids)
    {
        $qry = $this->newQuery();
        $qry = $qry->whereIn('id', $ids);
        return $qry->get();
    }
}
