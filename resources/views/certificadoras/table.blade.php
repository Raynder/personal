<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-header">
            <tr class="sticky-top">
                <th>Nome</th>
                <th>Site</th>
                <th>Telefone</th>
                <th width="150">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($certificadoras as $obj)
                <tr id="row_{{ $obj->id }}" class="@if ($obj->deleted_at != null) table-danger @endif">
                    <td>{{ $obj->nome }}</td>
                    <td><a href="{{ $obj->site }}" target="_blank">{{ $obj->site }} <i
                                class="fa fa-external-link-alt"></i></a></td>
                    <td>{{ $obj->telefone }}</td>
                    <td width="150">
                        <div class='btn-group'>
                            <a href="javascript:void(0);"
                                onclick="Tela.abrirJanela('{{ route('certificadoras.edit', [$obj->id]) }}', 'Editar Novidade', 'lg')"
                                class="btn btn-secondary btn-xs">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                        <div class='btn-group'>
                            <a href="javascript:void(0);"
                                onclick="Tela.abrirJanelaExcluir('{{ route('certificadoras.destroy', [$obj->id]) }}?_token={{ csrf_token() }}', '{{ $obj->id }}')"
                                class="btn btn-danger btn-xs">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Nenhum registro encontrado</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="card-footer clearfix">
    <div class="d-flex justify-content-end align-items-center gap-4">
        @include('layouts.pagination', [
            'paginator' => $certificadoras,
            'filtro' => '&' . http_build_query(request()->except('page')),
        ])
        <div class="d-flex gap-4">
            <div class="d-flex align-items-center " title="Quantidade de registros por página">
                <label for="filter_take" class="pagination-form-control-label">Linhas por página:</label>
                <select name="pagination_filter_take" id="pagination_filter_take" class="pagination-form-control"
                    onchange="$('#filter_take').val(this.value); $('#formSearch').submit();">
                    <option value="10" @if ($filter_take == 10) selected @endif>10</option>
                    <option value="20" @if ($filter_take == 20) selected @endif>20</option>
                    <option value="50" @if ($filter_take == 50) selected @endif>50</option>
                    <option value="100" @if ($filter_take == 100) selected @endif>100</option>
                    <option value="200" @if ($filter_take == 200) selected @endif>200</option>
                    <option value="500" @if ($filter_take == 500) selected @endif>500</option>
                </select>
            </div>
        </div>
    </div>
</div>
