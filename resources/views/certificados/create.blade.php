@include('adminlte-templates::common.errors')
{!! Form::open([
    'route' => 'certificados.store',
    'id' => 'form',
    'component' => 'certificados',
    'autocomplete' => 'off',
    'encType' => 'multipart/form-data'
]) !!}
<div class="row">
    @include('certificados.fields')
    <x-form-buttons :create="true" />
</div>
{!! Form::close() !!}
<script>
    $(function() {
        $("#form").submit(function(e) {
            Ajax.salvarRegistroComArquivo($(this), atualizarTela);
            e.preventDefault();
        });
    });
</script>
