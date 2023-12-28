@include('adminlte-templates::common.errors')
{!! Form::model($usuario, [
    'route' => ['exercicios.update', $usuario->id],
    'id' => 'form',
    'component' => 'usuarios',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    @include('exercicios.fields')
    <x-form-buttons :create="false" />
</div>
{!! Form::close() !!}
<div id="treino_clone" data-idx="_clone" class="treino-div" style="display:none;">
    <table>
        <tbody>
            @include('exercicios.treino', ['treino' => null, 'i' => '_clone'])
        </tbody>
    </table>
</div>
<script>
    $(function() {
        $("#form").submit(function(e) {
            Ajax.salvarRegistro($(this));
            e.preventDefault();
        });
    });
</script>
