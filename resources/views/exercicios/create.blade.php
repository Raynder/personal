@include('adminlte-templates::common.errors')
{!! Form::open([
    'route' => 'exercicios.store',
    'id' => 'form',
    'component' => 'exercicios',
    'autocomplete' => 'off',
]) !!}
<div class="row">
    @include('exercicios.fields')
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
