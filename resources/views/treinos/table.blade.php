<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-header">
            <tr class="sticky-top">
                <th>Nome</th>
                <th>#Usuários</th>
                <th>#Alunos</th>
                <th class="text-center">Opções</th>
            </tr>
        </thead>
        <tbody>
            @forelse($treinos as $obj)
                <tr id="row_{{ $obj->id }}" class="@if ($obj->deleted_at != null) table-danger @endif">
                    <td>{{ $obj->nome }}</td>
                    <td>{{ $obj->usuarios->count() }}</td>
                    <td>{{ $obj->alunos_count }}</td>
                    <td width="150" class="text-center">
                        <div class='btn-group'>
                            <a href="javascript:void(0);"
                                onclick="Tela.abrirJanela('{{ route('treinos.edit', [$obj->id]) }}', 'Editar  Treino', 'lg')" class="btn-table btn-xs">
                                <i class="fa fa-pencil-alt"></i>
                            </a>
                        </div>
                        <div class='btn-group'>
                            <a href="javascript:void(0);"
                                onclick="Tela.abrirJanelaExcluir('{{ route('treinos.destroy', [$obj->id]) }}?_token={{ csrf_token() }}', '{{ $obj->id }}')">
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
            'paginator' => $treinos,
            'filtro' => '&' . http_build_query(request()->except('page')),
        ])
    </div>
</div>
<script>
    $(function() {
        registerTooltip();
    });
</script>
