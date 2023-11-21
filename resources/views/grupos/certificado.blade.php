<tr id="certificado{{ $i }}" data-idx="{{ $i }}" class="linha-certificado">
    <td>
        <select name="certificados[{{ $i }}][certificado_id]" class="form-select">
            @if ($certificado !== null)
                <option value="{{ $certificado->id }}">{{ $certificado->razao_social }}</option>
            @endif
        </select>
    </td>
    <td>
        <button type="button" class="btn btn-xs btn-danger btn-table-action btn_excluir_certificado{{ $i }}">X
        </button>
        <script>
            $('button.btn_excluir_certificado{{ $i }}').bind('click', excluirCertificado);
        </script>
        {!! Form::hidden("certificados[$i][id]", isset($certificado) ? $certificado->id : '') !!}
        {!! Form::hidden("certificados[$i][deleted]", 'false') !!}
        {!! Form::hidden("certificados[$i][new]", isset($certificado) && isset($certificado->id) ? 'false' : 'true') !!}
    </td>
</tr>

<script>
    $(function() {
        @if ($certificado)
            Filtro.inicializaCampoBusca(
                '{{ route('certificados.find') }}',
                "select[name$='certificados[{{ $i }}][certificado_id]']",
                "Informe o Certificado",
                "#modalBasic"
            );
        @endif
    });
</script>
