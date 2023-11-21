@include('adminlte-templates::common.errors')

<div class="row">
    <div class="form-group col-md-12 alert-warning"
        style="margin-bottom: 20px; font-size: 0.8rem; border-radius: 10px; padding-top: 10px;">
        <p>Se não informar a data, a validade será de 1 ano a partir de hoje.</p>
        <p>Se o e-mail ficar em branco, será gerado um token em tela para cópia e utilização direta no PC.</p>
    </div>
</div>
<form action="{{ route('certificado.sendMail') }}" method="post" id="form">
    @csrf
    <div class="row">
        <div class="form-group col-sm-12">
            {!! Form::label('data_limite', 'Data para expirar a permissão de uso do certificado:') !!}
            {!! Form::date('data_limite', '', ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="row mt-3">
        <div class="form-group col-sm-12">
            {!! Form::label('mail', 'Email para receber o link de instalação (Opicional):') !!}
            {!! Form::email('mail', null, ['class' => 'form-control', 'maxlength' => 150, '']) !!}
        </div>
        <input type="hidden" name="id" value="{{ $certificado->id }}">
        <div id="chave">

        </div>
        <x-form-buttons :create="true" />
    </div>
</form>
<script>
    $(function() {
        $("#form").submit(function(e) {
            $.ajax({
                url: "{{ route('certificado.sendMail') }}",
                method: "POST",
                data: $("#form").serialize(),
                encoding: "UTF-8",
                success: function(response) {
                    $("#chave").html("</br>Chave gerada: <b>" + response.chave + "</b>");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Ajax.tratarErroAjax(jqXHR.status, jqXHR.responseText);
                    Tela.fecharModal();
                }
            });
            e.preventDefault();
        });
    });
</script>
