<?php

namespace App\Helpers;

class TableHelper
{
    public static function sortable_column($column, $title, $text_hint = '', $class = '', $extra = '')
    {
        $sortOriginal = $column;
        $icon = 'fa fa-sort';
        $asc_suffix = '-up';
        $desc_suffix = '-down';

        if (request()->get('filter_sort') == $sortOriginal && in_array(request()->get('filter_order'), ['asc', 'desc'])) {
            $icon = $icon . (request()->get('filter_order') === 'asc' ? $asc_suffix : $desc_suffix);
        }
        $parameters = [
            'filter_sort' => $sortOriginal,
            'filter_order' => request()->get('filter_order') === 'desc' ? 'asc' : 'desc',
        ];
        $queryString = http_build_query(array_merge(request()->except('sort', 'order', 'filter_sort', 'filter_order'), $parameters));
        $hint = '';
        if (isset($text_hint)) {
            $hint = 'title="' . $text_hint . '"';
        }

        return '<th class="sortable ' . $class . '" ' . $hint . ' ' . $extra . '><a href="' . url(request()->path() . '?' . $queryString) . '"' . '>' .
            $title . '</a>' . ' ' . '<i class="' . $icon . '"></i></th>';
    }
}
