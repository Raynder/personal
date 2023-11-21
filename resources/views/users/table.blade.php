<div class="table-responsive">
    <table class="table table-hover" id="users-table">
        <thead class="table-header">
            <tr class="sticky-top">
                {!! App\Helpers\TableHelper::sortable_column('name', 'Nome') !!}
                {!! App\Helpers\TableHelper::sortable_column('email', 'E-mail') !!}
                <th>Empresa</th>
                <th>Grupo</th>
                <th class="text-center">Opções</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $obj)
                <tr id="row_{{ $obj->id }}">
                    <td>{{ $obj->name }}</td>
                    <td>{{ $obj->email }}</td>
                    <td>
                        @if ($obj->empresa)
                            {{ $obj->empresa->razao_social }}<br />
                            {{ $obj->empresa->fantasia }} <br />
                            {{ App\Helpers\FormatterHelper::formatCnpjCpf($obj->empresa->cnpj) }}
                        @else
                            Administrador
                        @endif
                    </td>
                    <td>{{ count($obj->roles) > 0 ? $obj->roles->first()->name : '' }}</td>
                    <td width="120" class="text-center">
                        @if ($obj->deleted_at != null)
                            <div class="alert alert-danger-soft show flex items-center " role="alert">
                                Excluído
                            </div>
                        @else
                            <div class='btn-group'>
                                @can('utilitarios.usuarios')
                                    @if (!session()->has('empresa_id'))
                                        <a href="{{ route('users.impersonate', [$obj->id]) }}" class='btn-table btn-xs'
                                            data-bs-toggle="tooltip" data-color="primary" data-bs-placement="top"
                                            data-bs-original-title="Assumir controle">
                                            <i class='bx bx-user-voice text-primary'></i>
                                        </a>
                                    @endif
                                    <a href="javascript:void(0);"
                                        onclick="Tela.abrirJanela('{{ route('users.show', [$obj->id]) }}', 'Detalhes do Cadastro', 'md')"
                                        class='btn-table btn-xs'data-bs-toggle="tooltip" data-color="primary"
                                        data-bs-placement="top" data-bs-original-title="Exibir">
                                        <i class="far fa-eye"></i>
                                    </a>
                                @endcan
                                @can('utilitarios.usuarios.alterar')
                                    <a href="javascript:void(0);"
                                        onclick="Tela.abrirJanela('{{ route('users.edit', [$obj->id]) }}', 'Atualizar Usuário', 'md')"
                                        class='btn-table btn-xs'data-bs-toggle="tooltip" data-color="primary"
                                        data-bs-placement="top" data-bs-original-title="Alterar">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                @endcan
                                @can('utilitarios.usuarios.alterar')
                                    <a href="javascript:void(0);"
                                        onclick="Tela.abrirJanela('{{ route('users.updatePasswordFormAdmin', [$obj->id]) }}', 'Atualizar Senha', 'md')"
                                        class='btn-table btn-xs'data-bs-toggle="tooltip" data-color="primary"
                                        data-bs-placement="top" data-bs-original-title="Redefinir Senha">
                                        <i class="fa fa-key"></i>
                                    </a>
                                @endcan
                                @can('utilitarios.usuarios.excluir')
                                    <a href="javascript:void(0);" class="btn-table btn-table-danger btn-xs"
                                        data-bs-toggle="tooltip" data-color="danger" data-bs-placement="top"
                                        data-bs-original-title="Excluir"
                                        onclick="Tela.abrirJanelaExcluir('{{ route('users.destroy', [$obj->id]) }}?_token={{ csrf_token() }}', '{{ $obj->id }}')">
                                        <i class="fa fa-trash-alt"></i>
                                    </a>
                                @endcan
                            </div>
                        @endif
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
            'paginator' => $users,
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
