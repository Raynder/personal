@include('adminlte-templates::common.errors')
{!! Form::open([
    'route' => 'permissions.store',
    'id' => 'form',
    'component' => 'permissions',
    'autocomplete' => 'off',
]) !!}
<div class="row">
    @include('price::permissions.fields')
    <x-form-buttons :create="true" />
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
