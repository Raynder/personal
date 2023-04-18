@include('adminlte-templates::common.errors')
{!! Form::open([
    'route' => 'treinos.store',
    'id' => 'form',
    'component' => 'treinos',
    'autocomplete' => 'off',
]) !!}
<div class="row">
    @include('treinos.fields')
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
