@include('adminlte-templates::common.errors')
<div class="row">
    <div class="col-md-12">
        CNPJ do Certificado: {{ $certificado->cnpj }}
    </div>
</div>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">
        <h6>Usuários/Computadores que instalaram o certificado</h6>
        @include('alunos.acessos')
    </div>
</div>
