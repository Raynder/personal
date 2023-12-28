<script>
    $(function() {
        // $(".select2-padrao").select2({
        //     dropdownParent: "#modalBasic"
        // });
        // $("#cnpj").focus();
        //Input.cnpjCpf("#cnpj");
        // @if (isset($certificado))
        //     document.getElementById("cnpj").setAttribute('readonly', true);
        // @endif

        // $("#chkMaster").change(function() {
        //     if ($(this).is(':checked')) {
        //         $("#divSenhaMaster").show();
        //         $("#divUsuarioExistente").hide();
        //     } else {
        //         $("#divSenhaMaster").hide();
        //         $("#divUsuarioExistente").show();
        //     }
        // });
    });
</script>

<div class="row">
    <div class="form-group col-sm-6">
        {!! Form::label('nome', 'Nome:') !!}
        {!! Form::text('nome', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-6">
        {!! Form::label('email', 'Email:') !!}
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-6">
        {!! Form::label('senha', 'Senha:') !!}
        {!! Form::text('senha', null, ['class' => 'form-control senha']) !!}
    </div>

</div>

<div class="row" style="display:none">
    <div class="form-group col-sm-4">
        <label for="cnpj">CNPJ/CPF:</label>
        <input class="form-control" id="cnpj"
            @if (!isset($certificado)) onblur="Utils.buscarDadosFlytax(this.value, '{{ route('alunos.empresa') }}')"
            @else
                readonly @endif
            name="cnpj" type="text" value="{{ $certificado->cnpj ?? '' }}">
    </div>
    <div class="form-group col-sm-8">
        <label for="razao_social">Razão Social:</label>
        <input class="form-control" id="razao_social" name="razao_social" type="text"
            value="{{ $certificado->razao_social ?? '' }}" @if (isset($certificado)) readonly @endif>
    </div>
</div>
<div class="row hidden" style="display:none;">
    <div class="form-group col-sm-6">
        {!! Form::label('fantasia', 'Fantasia:') !!}
        {!! Form::text('fantasia', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>

</div>
