<table class="table table-responsive table-hover">
    <thead class="table-dark">
        <tr>
            <th>NOME</th>
            <th>MUSCULO</th>
            <th>TIPO</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tbody>
        @forelse($exercicios as $obj)
            <tr id="row_{{ $obj->id }}" class="@if ($obj->deleted_at != null) table-danger @endif">
                <td>{{ $obj->nome }}</td>
                <td>{{ $obj->musculo }}</td>
                <td>{{ $obj->tipo }}</td>
                <td width="150">
                    <div class='btn-group'>
                        <a href="javascript:void(0);"
                            onclick="Tela.abrirJanela('{{ route('exercicios.edit', [$obj->id]) }}', 'Editar Exercicio', 'lg')" class="btn btn-secondary btn-xs">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    <div class='btn-group'>
                        <a href="javascript:void(0);"
                            onclick="Tela.abrirJanelaExcluir('{{ route('exercicios.destroy', [$obj->id]) }}?_token={{ csrf_token() }}', '{{ $obj->id }}')" class="btn btn-danger btn-xs">
                            <i class="fas fa-trash"></i>
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
