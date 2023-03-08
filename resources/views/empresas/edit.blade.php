@include('adminlte-templates::common.errors')
{!! Form::model($empresa, [
    'route' => ['empresas.update', $empresa->id],
    'id' => 'form',
    'component' => 'empresas',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    @include('empresas.fields')
    <x-form-buttons :create="false" />
</div>
{!! Form::close() !!}
<script>
    $(function() {
        $("#form").submit(function(e) {
            Ajax.salvarRegistroComArquivo($(this));
            e.preventDefault();
        });
    });
</script>
