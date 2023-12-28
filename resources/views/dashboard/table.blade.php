<form name="fmCertificados" id="fmCertificados">
    @csrf
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-header">
                <tr class="sticky-top">
                    <th style="background-color: #677788 !important; color: white;">Nome</th>
                    <th class="text-center" style="background-color: #677788 !important; color: white;">E-mail</th>
                    <th class="text-center" style="background-color: #677788 !important; color: white;">Opções</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alunos as $obj)
                    <tr id="row_{{ $obj->id }}">
                        <td>{{ $obj->razao_social }}</td>
                        <td>{{ $obj->email }}</td>
                        <td width="150" class="text-center">
                            <div class='btn-group'>
                                @if (!$obj->aluno)
                                    <a href="javascript:void(0);"
                                        onclick="Tela.abrirJanela('{{ route('alunos.sendTokenForm', [$obj->id]) }}', 'Enviar Token', 'xs')"
                                        class="btn-table btn-xs">
                                        <i class="fas fa-key"></i>
                                    </a>
                                @endif
                                @if ($obj->aluno)
                                    <a href="javascript:void(0);"
                                        onclick="Tela.abrirJanela('{{ route('alunos.createChave', [$obj->id]) }}', 'Chave de Acesso:', 'xs')"
                                        class="btn-table btn-xs">
                                        <i class="fas fa-key"></i>
                                    </a>
                                    <a href="javascript:void(0);"
                                        onclick="Tela.abrirJanela('{{ route('alunos.show', [$obj->id]) }}', 'Visualizar Certificado', 'lg')"
                                        class="btn-table btn-xs">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                @endif
                                <a href="javascript:void(0);"
                                    onclick="Tela.abrirJanela('{{ route('alunos.edit', [$obj->id]) }}', 'Editar Certificado', 'lg')"
                                    class="btn-table btn-xs">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                                <a href="javascript:void(0);"
                                    onclick="Tela.abrirJanelaExcluir('{{ route('alunos.destroy', [$obj->id]) }}?_token={{ csrf_token() }}', '{{ $obj->id }}')"
                                    class="btn-table btn-table-danger btn-xs">
                                    <i class="fa fa-trash-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>Nenhum registro encontrado</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</form>
<div class="card-footer clearfix">
    <div class="d-flex justify-content-end align-items-center gap-4">
        @include('layouts.pagination', [
            'paginator' => $alunos,
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
