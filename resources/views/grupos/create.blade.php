@include('adminlte-templates::common.errors')
{!! Form::open([
    'route' => 'grupos.store',
    'id' => 'form',
    'component' => 'grupos',
    'autocomplete' => 'off',
    'enctype' => 'multipart/form-data'
]) !!}
<div class="row">
    @include('grupos.fields')
    <x-form-buttons :create="true" />
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
