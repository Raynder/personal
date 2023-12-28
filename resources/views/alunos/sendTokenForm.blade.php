@include('adminlte-templates::common.errors')
{!! Form::model($certificado, [
    'route' => ['alunos.sendToken', $certificado->id],
    'id' => 'form',
    'component' => 'certificados',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    <script>
        $(function() {
            $("#cnpj").focus();
        });
    </script>

    <div class="row">
        <div class="form-group col-sm-12 required">
            <label for="email">E-mail:</label>
            <input class="form-control" id="email" required="" name="email" type="text"
                value="{{ $certificado->email ?? '' }}">
        </div>
    </div>

    <div class="form-group col-sm-12 d-flex justify-content-between mt-4" id="footerModal">
        <div>
        </div>
        <div>
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="fa fa-check"></i> Enviar
            </button>
            <button type="button" class="btn btn-sm btn-label-secondary" data-bs-dismiss="modal">
                <i class="fa fa-ban"></i> Cancelar
            </button>
        </div>
    </div>

</div>
{!! Form::close() !!}
<script>
    $(function() {
        $("#form").submit(function(e) {
            Ajax.salvarRegistro($(this), atualizarTela);
            e.preventDefault();
        });
    });
</script>
