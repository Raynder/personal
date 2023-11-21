@include('adminlte-templates::common.errors')
{!! Form::model($certificado, [
    'route' => ['certificados.update', $certificado->id],
    'id' => 'form',
    'component' => 'certificados',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    @include('certificados.fields')
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
