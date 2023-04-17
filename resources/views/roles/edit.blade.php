@include('adminlte-templates::common.errors')
{!! Form::model($role, [
    'route' => ['roles.update', $role->id],
    'id' => 'form',
    'component' => '$role',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    @include('price::roles.fields')
    <x-form-buttons :create="false" />
</div>
{!! Form::close() !!}
<script>
    $(function() {
        $("#form").submit(function(e) {
            Ajax.salvarRegistro($(this));
            e.preventDefault();
        });
    });
</script>
