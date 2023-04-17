<div class="table-responsive">
    <table class="table table-hover" id="roles-table">
        <thead class="table-header">
            <tr class="sticky-top">
                {!! App\Helpers\TableHelper::sortable_column('name', 'Nome') !!}
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $obj)
                <tr id="row_{{ $obj->id }}" class="@if ($obj->deleted_at != null) table-danger @endif">
                    <td>{{ $obj->name }}</td>
                    <td width="120">
                        <div class='btn-group'>
                            @can('utilitarios.grupos.permissoes')
                                <a href="#" class='btn-table btn-xs' data-bs-toggle="tooltip" data-color="primary"
                                    data-bs-placement="top" data-bs-original-title="Ver permissões"
                                    onclick="Tela.abrirJanela('{{ route('roles.permissionForm', $obj->id) }}', 'Parametrizar as permissões do Grupo', 'md-6')">
                                    <i class="fas fa-lock"></i>
                                </a>
                            @endcan
                            @can('utilitarios.grupos.alterar')
                                <a href="javascript:void(0);" data-bs-toggle="tooltip" data-color="primary"
                                    data-bs-placement="top" data-bs-original-title="Alterar"
                                    onclick="Tela.abrirJanela('{{ route('roles.edit', [$obj->id]) }}', 'Atualizar Permissões do Grupo', 'md-6')"
                                    class='btn-table btn-xs'>
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                            @endcan
                            @can('utilitarios.grupos.excluir')
                                <a href="javascript:void(0);" class="btn-table btn-table-danger btn-xs"
                                    data-bs-toggle="tooltip" data-color="danger" data-bs-placement="top"
                                    data-bs-original-title="Excluir"
                                    onclick="Tela.abrirJanelaExcluir('{{ route('roles.destroy', [$obj->id]) }}?_token={{ csrf_token() }}', '{{ $obj->id }}')">
                                    <i class="fa fa-trash-alt"></i>
                                </a>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">Nenhum registro encontrado</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
<div class="card-footer clearfix">
    <div class="d-flex justify-content-end align-items-center gap-4">
        @include('layouts.pagination', [
            'paginator' => $roles,
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
                </select>
            </div>
        </div>
    </div>
</div>
<script>
    registerTooltip();
</script>
