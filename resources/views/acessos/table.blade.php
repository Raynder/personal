<table class="table table-responsive table-hover">
    <thead class="table-dark">
        <tr>
            <th>Empresa</th>
            <th>Status</th>
            <th>Usuario</th>
            <th>UUID</th>
        </tr>
    </thead>
    <tbody>
        @forelse($acessos as $obj)
            <tr id="row_{{ $obj->id }}" class="@if ($obj->deleted_at != null) table-danger @endif">
                <td>{{ App\Helpers\FormatterHelper::formatCnpjCpf($obj->certificado->cnpj) }} |
                    {{ $obj->certificado->fantasia }}</td>
                <td>
                    <form action="{{ route('acessos.status', [$obj->id]) }}" method="GET" id="form_{{ $obj->id }}"
                        class="form-inline">
                        @csrf

                        <button type="button" style="border: none; background: none;" class="btn btn-sm btn-secondary" onclick="Tela.abrirJanela('{{ route('acessos.opcoes', [$obj->id]) }}', 'Atualizar Acesso', 'xs')">
                            <i class="{{ App\Helpers\StatusAcessoHelper::getIcone($obj->status) }}"
                                    data-bs-toggle="tooltip" data-placement="top" data-color="secondary"
                                    data-bs-original-title="{{ App\Helpers\StatusAcessoHelper::getStatus($obj->status) }}"
                                    title="{{ App\Helpers\StatusAcessoHelper::getStatus($obj->status) }}"></i>
                        </button>
                    </form>
                </td>
                <td>{{ $obj->usuario }}</td>
                <td>{{ $obj->uuid_usuario }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">Nenhum acesso encontrado</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="card-footer clearfix">
    <div class="float-right">
        @include('layouts.pagination', [
            'paginator' => $acessos,
            'filtro' => '&' . http_build_query(request()->except('page')),
        ])
    </div>
</div>
<script>
    $(function() {
        registerTooltip();
        // Ao clicar no botão de status
        $('button[type="submit"]').on('click', function(e) {
            e.preventDefault();
            // Pega o form
            var form = $(this).closest('form');
            // Pega o id da certificado
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
