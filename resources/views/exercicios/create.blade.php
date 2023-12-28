@include('adminlte-templates::common.errors')
{!! Form::open([
    'route' => 'exercicios.store',
    'id' => 'form',
    'component' => 'exercicios',
    'autocomplete' => 'off',
    'enctype' => 'multipart/form-data'
]) !!}
<div class="row">
    @include('exercicios.fields')
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
