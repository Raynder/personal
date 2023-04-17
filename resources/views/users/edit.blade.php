@include('adminlte-templates::common.errors')
{!! Form::model($owner, [
    'route' => ['users.update', $owner->id],
    'id' => 'form',
    'component' => '$owner',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    @include('price::users.fields')
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
