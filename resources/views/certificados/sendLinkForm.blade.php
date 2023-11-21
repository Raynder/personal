@include('adminlte-templates::common.errors')
{!! Form::open([
    'route' => 'certificados.sendLink',
    'id' => 'form',
    'component' => 'certificados',
    'autocomplete' => 'off',
]) !!}
<div class="row">
    <script>
        $(function() {
            $("#cnpj").focus();
        });
    </script>

    <div class="row">
        <div class="form-group col-sm-4">
            <label for="cnpj">CNPJ:</label>
            <input class="form-control" id="cnpj" required="" name="cnpj" type="text"
                value="{{ $certificado->cnpj ?? '' }}">
        </div>
        <div class="form-group col-sm-8">
            <label for="razao_social">Razão Social:</label>
            <input class="form-control" id="razao_social" required="" name="razao_social" type="text"
                value="{{ $certificado->razao_social ?? '' }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="email">E-mail:</label>
            <input class="form-control" id="email" required="" name="email" type="text"
                value="{{ $certificado->email ?? '' }}">
        </div>
    </div>
    <div class="row hidden" style="display:none;">
        <div class="form-group col-sm-6">
            {!! Form::label('fantasia', 'Fantasia:') !!}
            {!! Form::text('fantasia', null, ['class' => 'form-control', 'maxlength' => 150]) !!}
        </div>

    </div>

    <div class="form-group col-sm-12 d-flex justify-content-between mt-4" id="footerModal">
        <div>
        </div>
        <div>
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="fa fa-check"></i> Enviar link
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
        $("#cnpj").on('blur', function() {
            Utils.buscarDadosEmpresa($(this).val(), '{{ route('search.cnpj') }}');
        });
        $("#form").submit(function(e) {
            Ajax.salvarRegistro($(this), atualizarTela);
            e.preventDefault();
        });
    });
</script>
