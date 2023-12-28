<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-header">
            <tr class="sticky-top">
                {!! App\Helpers\TableHelper::sortable_column('cnpj', 'Cnpj/Fantasia') !!}
                <th>Total</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>
            @forelse($empresas as $obj)
                <tr id="row_{{ $obj->id }}" class="@if ($obj->deleted_at != null) table-danger @endif">
                    <td>{{ App\Helpers\FormatterHelper::formatCnpjCpf($obj->cnpj) }} | {{ $obj->fantasia }}</td>
                    <td>{{ $obj->alunos_count }} Certificados</td>
                    <td width="120" class="text-center">
                        <a href="javascript:void(0);"
                            onclick="Tela.abrirJanela('{{ route('empresas.edit', [$obj->id]) }}', 'Editar Empresa', 'lg')"
                            class="btn-table btn-xs">
                            <i class="fa fa-pencil-alt"></i>
                        </a>
                        <a href="javascript:void(0);"
                            onclick="Tela.abrirJanelaExcluir('{{ route('empresas.destroy', [$obj->id]) }}?_token={{ csrf_token() }}', '{{ $obj->id }}')"
                            class="btn-table btn-table-danger btn-xs">
                            <i class="fa fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Nenhum registro encontrado</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="card-footer clearfix">
    <div class="d-flex justify-content-end align-items-center gap-4">
        @include('layouts.pagination', [
            'paginator' => $empresas,
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

<script>
    $(function() {
        // Ao clicar no botão de status
        $('button[type="submit"]').on('click', function(e) {
            e.preventDefault();
            // Pega o form
            var form = $(this).closest('form');
            // Pega o id da empresa
            var id = form.attr('id').replace('form_', '');
            var rota = form.attr('action');
            $.ajax({
                url: rota,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'PUT',
                    id: id
                },

                success: function(response) {
                    Tela.avisoComSucesso(response);
                    $('#formSearch').submit();
                },
                error: function(response) {
                    Tela.avisoComErro(response.responseJSON);
                }
            });
        });
    });
</script>
