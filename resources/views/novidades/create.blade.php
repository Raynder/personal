@include('adminlte-templates::common.errors')
{!! Form::open([
    'route' => 'novidades.store',
    'id' => 'form',
    'component' => 'novidades',
    'autocomplete' => 'off',
    'enctype' => 'multipart/form-data'
]) !!}
<div class="row">
    @include('novidades.fields')
    <x-form-buttons :create="true" />
</div>
{!! Form::close() !!}
<script>
    $(function() {
        $("#form").submit(function(e) {
            Ajax.salvarRegistroComArquivo($(this));
            e.preventDefault();
        });
    });
</script>
