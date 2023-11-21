<tr id="grupo{{ $i }}" data-idx="{{ $i }}" class="linha-grupo">
    <td>
        <select name="grupos[{{ $i }}][grupo_id]" class="form-select">
            @if ($grupo !== null)
                <option value="{{ $grupo->id }}">{{ $grupo->nome }}</option>
            @endif
        </select>
    </td>
    <td>
        <button type="button" class="btn btn-sm btn-secondary btn-table-action "
            onclick="Tela.abrirJanela('{{ route('grupos.create') }}', 'Nova Classificação', 'md', true)">
            <i class="fa fa-plus"></i>
        </button>
    </td>
    <td>
        <button type="button"
            class="btn btn-xs btn-danger btn-table-action btn_excluir_grupo{{ $i }}">X
        </button>
        <script>
            $('button.btn_excluir_grupo{{ $i }}').bind('click', excluirGrupo);
        </script>
        {!! Form::hidden("grupos[$i][id]", isset($grupo) ? $grupo->id : '') !!}
        {!! Form::hidden("grupos[$i][deleted]", 'false') !!}
        {!! Form::hidden(
            "grupos[$i][new]",
            isset($grupo) && isset($grupo->id) ? 'false' : 'true',
        ) !!}
    </td>
</tr>

<script>
    $(function() {
        @if ($grupo)
            Filtro.inicializaCampoBusca(
                '{{ route('grupos.find') }}',
                "select[name$='grupos[{{ $i }}][grupo_id]']",
                "Informe a descrição",
                "#modalBasic"
            );
        @endif
    });
</script>
