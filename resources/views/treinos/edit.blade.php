@include('adminlte-templates::common.errors')
{!! Form::model($treino, [
    'route' => ['treinos.update', $treino->id],
    'id' => 'form',
    'component' => 'treinos',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    @include('treinos.fields')
    <x-form-buttons :create="false" />
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
