@include('adminlte-templates::common.errors')
{!! Form::model($grupo, [
    'route' => ['grupos.update', $grupo->id],
    'id' => 'form',
    'component' => 'grupos',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    @include('grupos.fields')
    <x-form-buttons :create="false" />
</div>
{!! Form::close() !!}
<div id="certificado_clone" data-idx="_clone" class="certificado-div" style="display:none;">
    <table>
        <tbody>
            @include('grupos.certificado', ['certificado' => null, 'i' => '_clone'])
        </tbody>
    </table>
</div>
<script>
    $(function() {
        $("#form").submit(function(e) {
            Ajax.salvarRegistro($(this));
            e.preventDefault();
        });
    });
</script>
