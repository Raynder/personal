@include('adminlte-templates::common.errors')
{!! Form::open([
    'route' => 'certificadoras.store',
    'id' => 'form',
    'component' => 'certificadoras',
    'autocomplete' => 'off',
    'enctype' => 'multipart/form-data',
]) !!}
<div class="row">
    @include('certificadoras.fields')
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
