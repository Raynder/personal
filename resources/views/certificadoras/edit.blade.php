@include('adminlte-templates::common.errors')
{!! Form::model($certificadora, [
    'route' => ['certificadoras.update', $certificadora->id],
    'id' => 'form',
    'component' => 'certificadoras',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    @include('certificadoras.fields')
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
