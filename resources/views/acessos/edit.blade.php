@include('adminlte-templates::common.errors')
{!! Form::model($acesso, [
    'route' => ['acessos.update', $acesso->id],
    'id' => 'form',
    'component' => 'acessos',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    @include('acessos.fields')
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
