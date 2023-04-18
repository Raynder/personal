@include('adminlte-templates::common.errors')
{!! Form::model($exercicio, [
    'route' => ['exercicios.update', $exercicio->id],
    'id' => 'form',
    'component' => 'exercicios',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    @include('exercicios.fields')
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
