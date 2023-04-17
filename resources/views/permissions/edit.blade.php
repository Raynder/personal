@include('adminlte-templates::common.errors')
{!! Form::model($permission, [
    'route' => ['permissions.update', $permission->id],
    'id' => 'form',
    'component' => '$permission',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    @include('price::permissions.fields')
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
