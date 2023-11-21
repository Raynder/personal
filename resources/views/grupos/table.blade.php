<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-header">
            <tr class="sticky-top">
                <th>Nome</th>
                <th>#Usuários</th>
                <th>#Certificados</th>
                <th class="text-center">Opções</th>
            </tr>
        </thead>
        <tbody>
            @forelse($grupos as $obj)
                <tr id="row_{{ $obj->id }}" class="@if ($obj->deleted_at != null) table-danger @endif">
                    <td>{{ $obj->nome }}</td>
                    <td>{{ $obj->usuarios_count }}</td>
                    <td>{{ $obj->certificados_count }}</td>
                    <td width="150" class="text-center">
                        <div class='btn-group'>
                            <a href="javascript:void(0);"
                                onclick="Tela.abrirJanela('{{ route('grupos.edit', [$obj->id]) }}', 'Editar  Grupo', 'lg')" class="btn-table btn-xs">
                                <i class="fa fa-pencil-alt"></i>
                            </a>
                        </div>
                        <div class='btn-group'>
                            <a href="javascript:void(0);"
                                onclick="Tela.abrirJanelaExcluir('{{ route('grupos.destroy', [$obj->id]) }}?_token={{ csrf_token() }}', '{{ $obj->id }}')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Nenhum acesso encontrado</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<div class="card-footer clearfix">
    <div class="float-right">
        @include('layouts.pagination', [
            'paginator' => $grupos,
            'filtro' => '&' . http_build_query(request()->except('page')),
        ])
    </div>
</div>
<script>
    $(function() {
        registerTooltip();
    });
</script>
