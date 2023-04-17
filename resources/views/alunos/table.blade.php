<table class="table table-responsive table-hover">
    <thead class="table-dark">
        <tr>
            <th>NOME</th>
            <th>EMAIL</th>
            <th>CELULAR</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tbody>
        @forelse($alunos as $obj)
            <tr id="row_{{ $obj->id }}" class="@if ($obj->deleted_at != null) table-danger @endif">
                <td>{{ $obj->nome }}</td>
                <td>{{ $obj->email }}</td>
                <td>{{ $obj->celular }}</td>
                <td width="150">
                    <div class='btn-group'>
                        <a href="javascript:void(0);"
                            onclick="Tela.abrirJanela('{{ route('alunos.edit', [$obj->id]) }}', 'Editar Aluno', 'lg')" class="btn btn-secondary btn-xs">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    <div class='btn-group'>
                        <a href="javascript:void(0);"
                            onclick="Tela.abrirJanelaExcluir('{{ route('alunos.destroy', [$obj->id]) }}?_token={{ csrf_token() }}', '{{ $obj->id }}')" class="btn btn-danger btn-xs">
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
