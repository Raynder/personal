@include('adminlte-templates::common.errors')
{!! Form::model($treino, [
    'route' => ['treinos.update', $treino->id],
    'id' => 'form',
    'component' => 'treinos',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    @include('treinos.fields')
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
