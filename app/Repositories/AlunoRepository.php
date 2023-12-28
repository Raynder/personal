<?php

namespace App\Repositories;

use App\Models\Aluno;
use App\Repositories\AbstractCrudRepository;

class AlunoRepository extends AbstractCrudRepository
{
    protected $modelClass = Aluno::class;

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

    public function validate($params)
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
        if (isset($params['filter_dias_vencimento'])) {
            $inicio = $params['filter_dias_vencimento'] == 7 ? 1 : $params['filter_dias_vencimento'] - ($params['filter_dias_vencimento'] / 2) + 1;
            $inicio = $inicio > 20 ? 0 : $inicio;
            $qry = $qry->whereBetween('data_validade', [now()->addDays($inicio), now()->addDays($params['filter_dias_vencimento'])]);
        }
        $qry = $qry->orderBy('data_validade', 'asc');

        return $this->doQuery($qry, isset($params['filter_take']) ? $params['filter_take'] : 9999, true);
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
