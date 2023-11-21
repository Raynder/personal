@include('adminlte-templates::common.errors')
{!! Form::model($user, [
    'route' => ['users.update', $user->id],
    'id' => 'form',
    'component' => 'user',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    @include('users.fields')
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
