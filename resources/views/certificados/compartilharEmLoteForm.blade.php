<form action="{{ route('certificado.sendTokensByMail') }}" method="post" id="form">
    @csrf
    @foreach($ids as $id)
        <input type="hidden" name="certificado_id[]" value="{{ $id }}"/>
    @endforeach
    <div class="row">
        <div class="form-group col-sm-12 ">
            <h5>Compartilhando [{{ count($ids) }}] certificado(s)</h5>
        </div>

        <div class="form-group col-sm-5">
            {!! Form::label('data_limite', 'Expiração da chave:') !!}
            {!! Form::date('data_limite', '', ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-sm-7 ">
            {!! Form::label('mail', 'Email (Opicional):') !!}
            {!! Form::email('mail', null, ['class' => 'form-control', 'maxlength' => 150, '']) !!}
        </div>

        <div id="chave">

        </div>
        <x-form-buttons :create="true" />

    </div>
{!! Form::close() !!}
<script>
    $(function() {
        $("#form").submit(function(e) {
            $.ajax({
                url: "{{ route('certificado.sendTokensByMail') }}",
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

