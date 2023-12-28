@include('adminlte-templates::common.errors')
{!! Form::model($aluno, [
    'route' => ['alunos.update', $aluno->id],
    'id' => 'form',
    'component' => 'alunos',
    'autocomplete' => 'off',
    'method' => 'patch',
]) !!}
<div class="row">
    @include('alunos.fields')
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
