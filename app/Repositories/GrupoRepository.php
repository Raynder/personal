<?php

namespace App\Repositories;

use App\Models\Grupo;
use App\Repositories\AbstractCrudRepository;

class GrupoRepository extends AbstractCrudRepository
{
    protected $modelClass = Grupo::class;

    public function all($params)
    {
        $qry = $this->newQuery();

        $qry->withCount('certificados');
        $qry->withCount('usuarios');
        
        if (isset($params['filter_id'])) {
            $qry = $qry->where('id', '=', $params['filter_id']);
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
        $qry = $qry->where('empresa_id', session()->get('empresa_id'));
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
