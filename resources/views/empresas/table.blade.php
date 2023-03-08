<table class="table table-responsive table-hover">
    <thead class="table-dark">
        <tr>
            {!! App\Helpers\TableHelper::sortable_column('cnpj', 'Cnpj/Fantasia') !!}
            <th>Total</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tbody>
        @forelse($empresas as $obj)
            <tr id="row_{{ $obj->id }}" class="@if ($obj->deleted_at != null) table-danger @endif">
                <td>{{ App\Helpers\FormatterHelper::formatCnpjCpf($obj->cnpj) }} | {{ $obj->fantasia }}</td>
                <td>{{ $obj->certificados_count }} Certificados</td>
                <td width="120">
                    <div class='btn-group'>
                        <a href="javascript:void(0);"
                            onclick="Tela.abrirJanela('{{ route('empresas.edit', [$obj->id]) }}', 'Atualizar Empresa', 'lg')"
                            class='btn btn-secondary btn-xs'>
                            <i class="far fa-edit"></i>
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
