@include('adminlte-templates::common.errors')
{!! Form::model($novidade, [
    'route' => ['novidades.update', $novidade->id],
    'id' => 'form',
    'component' => 'novidades',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    @include('novidades.fields')
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
