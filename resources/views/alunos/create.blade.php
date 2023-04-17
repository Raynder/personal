@include('adminlte-templates::common.errors')
{!! Form::open([
    'route' => 'alunos.store',
    'id' => 'form',
    'component' => 'alunos',
    'autocomplete' => 'off',
]) !!}
<div class="row">
    @include('alunos.fields')
    <x-form-buttons :create="true" />
</div>
{!! Form::close() !!}
<script>
    $(function() {
        $("#form").submit(function(e) {
            Ajax.salvarRegistroComArquivo($(this), atualizarTela);
            e.preventDefault();
        });
    });
</script>
