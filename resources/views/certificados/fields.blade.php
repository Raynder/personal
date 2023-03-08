<script>
    $(function() {
        $(".select2-padrao").select2({
            dropdownParent: "#modalBasic"
        });
        $("#cnpj").focus();
        Input.telefone("#fone");
        Input.cnpj("#cnpj");
        Input.calendario(".data_abertura");
        @if (isset($certificado))
            document.getElementById("cnpj").setAttribute('readonly', true);
        @endif

        $("#chkMaster").change(function() {
            if ($(this).is(':checked')) {
                $("#divSenhaMaster").show();
                $("#divUsuarioExistente").hide();
            } else {
                $("#divSenhaMaster").hide();
                $("#divUsuarioExistente").show();
            }
        });
    });
</script>

<div class="row">
    <div class="form-group col-sm-8">
        {!! Form::label('certificado', 'Certificado Digital:') !!}
        {!! Form::file('certificado', ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-4">
        {!! Form::label('senha', 'Senha Certificado Digital:') !!}
        {!! Form::text('senha', null, ['class' => 'form-control senha']) !!}
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-4">
        <label for="cnpj">CNPJ:</label>
        <input class="form-control" id="cnpj" required=""
            onblur="Utils.buscarDadosFlytax(this.value, '{{ route('certificado.empresa') }}')" name="cnpj"
            type="text">
    </div>
    <div class="form-group col-sm-8">
        {!! Form::label('razao_social', 'Razão Social:') !!}
        {!! Form::text('razao_social', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>
</div>
<div class="row hidden" style="display:none;">
    <div class="form-group col-sm-6">
        {!! Form::label('fantasia', 'Fantasia:') !!}
        {!! Form::text('fantasia', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>

</div>
