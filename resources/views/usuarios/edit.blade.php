@include('adminlte-templates::common.errors')
{!! Form::model($usuario, [
    'route' => ['usuarios.update', $usuario->id],
    'id' => 'form',
    'component' => 'usuarios',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    @include('usuarios.fields')
    <x-form-buttons :create="false" />
</div>
{!! Form::close() !!}
<div id="grupo_clone" data-idx="_clone" class="grupo-div" style="display:none;">
    <table>
        <tbody>
            @include('usuarios.grupo', ['grupo' => null, 'i' => '_clone'])
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
