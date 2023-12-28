<script>
    $(function() {
        $(".select2-padrao").select2({
            dropdownParent: "#modalBasic"
        });

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
    <div class="form-group col-sm-4">
        {!! Form::label('razao_social', 'Razão Social:') !!}
        {!! Form::text('razao_social', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>

    <div class="form-group col-sm-4">
        {!! Form::label('fantasia', 'Fantasia:') !!}
        {!! Form::text('fantasia', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
    </div>
</div>

@if (!$empresa->id)
    <div class="row mt-3">
        <div class="form-check form-switch col-md-6 mb-2 mt-2">
            <input type="hidden" name="incluirMaster" value="N" />
            <input class="form-check-input" type="checkbox" id="chkMaster" name="incluirMaster" value="S"
                checked />
            <label class="form-check-label" for="chkMaster">Cadastrar Usuário Master</label>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6" id="divUsuarioExistente" style="display:none;">
            {!! Form::label('usuarioExistenteId', 'Informe o usuário a ser vinculado:') !!}
            <select class="form-control select2-padrao" id="usuarioExistenteId" name="usuarioExistenteId">
                <option value="">Informe o usuário</option>
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-sm-5 required">
            {!! Form::label('email', 'E-mail (Usuário Master):') !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'maxlength' => 150, 'required']) !!}
        </div>
        <div class="form-group col-sm-6" id="divSenhaMaster">
            {!! Form::label('senha', 'Senha do usuário Master:') !!}
            {!! Form::password('senha', ['class' => 'form-control', 'minlength' => 8, 'maxlength' => 30]) !!}
        </div>
    </div>
@endif
