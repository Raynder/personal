<?php
$link_limit = 7; // maximum number of links (a little bit inaccurate, but will be ok for now)
?>
@if ($paginator->lastPage() > 1)
    <ul class="pagination mb-0">
        <li class="page-item first {{ $paginator->currentPage() == 1 ? ' disabled' : '' }}">
            <a class="page-link" href="{{ str_replace('/filtro', '', $paginator->url(1)) . $filtro }}">««</a>
        </li>
        <li class="page-item prev {{ $paginator->currentPage() == 1 ? ' disabled' : '' }}">
            <a class="page-link"
                href="{{ str_replace('/filtro', '', $paginator->url($paginator->currentPage() - 1)) . $filtro }}">«</a>
        </li>
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <?php
            $half_total_links = floor($link_limit / 2);
            $from = $paginator->currentPage() - $half_total_links;
            $to = $paginator->currentPage() + $half_total_links;
            if ($paginator->currentPage() < $half_total_links) {
                $to += $half_total_links - $paginator->currentPage();
            }
            if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
                $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
            }
            ?>
            @if ($from < $i && $i < $to)
                <li class="page-item {{ $paginator->currentPage() == $i ? ' active' : '' }}">
                    <a class="page-link"
                        href="{{ str_replace('/filtro', '', $paginator->url($i)) . $filtro }}">{{ $i }}</a>
                </li>
            @endif
        @endfor
        <li class="page-item next {{ $paginator->currentPage() == $paginator->lastPage() ? ' disabled' : '' }}">
            <a class="page-link"
                href="{{ str_replace('/filtro', '', $paginator->url($paginator->currentPage() + 1)) . $filtro }}">»</a>
        </li>
        <li class="page-item last {{ $paginator->currentPage() == $paginator->lastPage() ? ' disabled' : '' }}">
            <a class="page-link"
                href="{{ str_replace('/filtro', '', $paginator->url($paginator->lastPage())) . $filtro }}">»»</a>
        </li>
    </ul>
@endif
