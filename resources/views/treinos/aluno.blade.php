<tr id="aluno{{ $i }}" data-idx="{{ $i }}" class="linha-aluno">
    <td>
        <select name="alunos[{{ $i }}][aluno_id]" class="form-select">
            @if ($aluno !== null)
                <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
            @endif
        </select>
    </td>
    <td>
        <button type="button" class="btn btn-xs btn-danger btn-table-action btn_excluir_aluno{{ $i }}">X
        </button>
        <script>
            $('button.btn_excluir_aluno{{ $i }}').bind('click', excluirCertificado);
        </script>
        {!! Form::hidden("alunos[$i][id]", isset($aluno) ? $aluno->id : '') !!}
        {!! Form::hidden("alunos[$i][deleted]", 'false') !!}
        {!! Form::hidden("alunos[$i][new]", isset($aluno) && isset($aluno->id) ? 'false' : 'true') !!}
    </td>
</tr>

<script>
    $(function() {
        @if ($aluno)
            Filtro.inicializaCampoBusca(
                '{{ route('alunos.find') }}',
                "select[name$='alunos[{{ $i }}][aluno_id]']",
                "Informe o Aluno",
                "#modalBasic"
            );
        @endif
    });
</script>
