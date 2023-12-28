<tr id="treino{{ $i }}" data-idx="{{ $i }}" class="linha-treino">
    <td>
        <select name="treinos[{{ $i }}][treino_id]" class="form-select">
            @if ($treino !== null)
                <option value="{{ $treino->id }}">{{ $treino->nome }}</option>
            @endif
        </select>
    </td>
    <td>
        <button type="button" class="btn btn-sm btn-secondary btn-table-action "
            onclick="Tela.abrirJanela('{{ route('treinos.create') }}', 'Nova Classificação', 'md', true)">
            <i class="fa fa-plus"></i>
        </button>
    </td>
    <td>
        <button type="button"
            class="btn btn-xs btn-danger btn-table-action btn_excluir_treino{{ $i }}">X
        </button>
        <script>
            $('button.btn_excluir_treino{{ $i }}').bind('click', excluirTreino);
        </script>
        {!! Form::hidden("treinos[$i][id]", isset($treino) ? $treino->id : '') !!}
        {!! Form::hidden("treinos[$i][deleted]", 'false') !!}
        {!! Form::hidden(
            "treinos[$i][new]",
            isset($treino) && isset($treino->id) ? 'false' : 'true',
        ) !!}
    </td>
</tr>

<script>
    $(function() {
        @if ($treino)
            Filtro.inicializaCampoBusca(
                '{{ route('treinos.find') }}',
                "select[name$='treinos[{{ $i }}][treino_id]']",
                "Informe a descrição",
                "#modalBasic"
            );
        @endif
    });
</script>
