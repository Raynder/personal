<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-header">
            <tr class="sticky-top">
                <th>Apelido</th>
                <th>Nome</th>
                <th>Cadastro</th>
                <th class="text-center">Opções</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $obj)
                <tr id="row_{{ $obj->id }}" class="@if ($obj->deleted_at != null) table-danger @endif">
                    <td><b>{{ $obj->apelido }}</b></td>
                    <td>{{ $obj->nome }}</td>
                    <td>{{ $obj->created_at->format('d/m/Y') }}</td>
                    <td width="150" class="text-center">
                        <div class='btn-group'>
                            <a href="javascript:void(0);"
                                onclick="Tela.abrirJanela('{{ route('exercicios.edit', [$obj->id]) }}', 'Editar  Usuário', 'lg')"
                                class="btn-table btn-xs">
                                <i class="fa fa-pencil-alt"></i>
                            </a>
                            <a href="javascript:void(0);"
                                onclick="Tela.abrirJanela('{{ route('exercicios.show', [$obj->id]) }}', 'Visualizar Acesso do Usuário', 'lg')"
                                class="btn-table btn-xs">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn-table btn-table-danger btn-xs"
                                onclick="Tela.abrirJanelaExcluir('{{ route('exercicios.destroy', [$obj->id]) }}?_token={{ csrf_token() }}', '{{ $obj->id }}')">
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
            'paginator' => $usuarios,
            'filtro' => '&' . http_build_query(request()->except('page')),
        ])
    </div>
</div>
<script>
    $(function() {
        registerTooltip();
    });
</script>
