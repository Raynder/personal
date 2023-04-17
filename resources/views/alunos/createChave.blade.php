@include('adminlte-templates::common.errors')

<form action="{{ route('aluno.sendMail') }}" method="post" id="form">
    @csrf
    <div class="row">
        <div class="form-group col-sm-5">
            {!! Form::label('data_limite', 'Expiração da chave:') !!}
            {!! Form::date('data_limite', '', ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-sm-7 ">
            {!! Form::label('mail', 'Email (Opicional):') !!}
            {!! Form::email('mail', null, ['class' => 'form-control', 'maxlength' => 150, '']) !!}
        </div>
        <input type="hidden" name="id" value="{{$aluno->id}}">
        <div id="chave">

        </div>
        <x-form-buttons :create="true" />
    </div>
</form>
<script>
    $(function() {
        $("#form").submit(function(e) {
            $.ajax({
                url: "{{ route('aluno.sendMail') }}",
                method: "POST",
                data: $("#form").serialize(),
                encoding: "UTF-8",
                success: function (response) {
                    $("#chave").html("</br>Chave gerada: <b>"+response.chave+"</b>");   
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Ajax.tratarErroAjax(jqXHR.status, jqXHR.responseText);
                    Tela.fecharModal();
                }
            });
            e.preventDefault();
        });
    });
</script>
