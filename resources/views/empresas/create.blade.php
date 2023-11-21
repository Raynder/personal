@include('adminlte-templates::common.errors')
{!! Form::open(['route' => 'empresas.store', 'id'=>'form', 'component'=> 'empresas', 'autocomplete' => 'off']) !!}
    <div class="row">
        @include('empresas.fields')
        <x-form-buttons :create="true"/>
    </div>
{!! Form::close() !!}
<script>
    $(function () {
        $("#form").submit(function (e) {
            Ajax.salvarRegistroComArquivo($(this));
            e.preventDefault();
        });
    });
</script>

