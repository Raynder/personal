<x-external-layout>

    @include('adminlte-templates::common.errors')
    {!! Form::open([
        'route' => 'external.customer.store',
        'id' => 'form',
        'component' => 'certificados',
        'autocomplete' => 'off',
        'encType' => 'multipart/form-data',
    ]) !!}
    <div class="row">
        <script>
            $(function() {
                $(".select2-padrao").select2({
                    dropdownParent: "#modalBasic"
                });
                $("#cnpj").focus();
                Input.telefone("#fone");
                Input.cnpjCpf("#cnpj");
                Input.calendario(".data_abertura");
                @if (isset($certificado))
                    document.getElementById("cnpj").setAttribute('readonly', true);
                @endif

                $("#chkMaster").change(function() {
                    if ($(this).is(':checked')) {
                        $("#divSenhaMaster").show();
                        $("#divUsuarioExistente").hide();
                    } else {
                        $("#divSenhaMaster").hide();
                        $("#divUsuarioExistente").show();
                    }
                });
            });
        </script>
        <div class="card" style="max-width: 600px; margin: 0 auto; margin-top: 20px;">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fa fa-search"></i> Anexe o certificado e informe a senha</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-sm-12">
                        {!! Form::label('certificado', 'Certificado Digital:') !!}
                        {!! Form::file('certificado', ['class' => 'form-control', 'required']) !!}
                    </div>
                </div>
                <div class="row mt-4">

                    <div class="form-group col-sm-12">
                        {!! Form::label('senha', 'Senha Certificado Digital:') !!}
                        {!! Form::text('senha', null, ['class' => 'form-control senha', 'required']) !!}
                    </div>
                </div>
                <div class="form-group col-sm-12 d-flex justify-content-between mt-4" id="footerModal">
                    <div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fa fa-check"></i> Salvar
                        </button>
                    </div>
                </div>

            </div>
        </div>

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
</x-external-layout>
