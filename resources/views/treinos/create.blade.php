@include('adminlte-templates::common.errors')
{!! Form::open([
    'route' => 'treinos.store',
    'id' => 'form',
    'component' => 'treinos',
    'autocomplete' => 'off',
    'enctype' => 'multipart/form-data'
]) !!}
<div class="row">
    @include('treinos.fields')
    <x-form-buttons :create="true" />
</div>
{!! Form::close() !!}
<div id="aluno_clone" data-idx="_clone" class="aluno-div" style="display:none;">
    <table>
        <tbody>
            @include('treinos.aluno', ['aluno' => null, 'i' => '_clone'])
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
