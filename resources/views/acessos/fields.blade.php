<div class="row">
    <div class="form-group col-sm-3">
        <label for="usuario">Usuário:</label>
        <input type="text" value="{{ $acesso->usuario }}" class="form-control" disabled>
    </div>

    <div class="form-group col-sm-6">
        <label for="certificado">Certificado:</label>
        <input type="text" value="{{ $acesso->certificado->razao_social }}" class="form-control" disabled>
    </div>


    <div class="form-group col-sm-3">
        <label for="cnpj">CNPJ:</label>
        <input type="text" value="{{ $acesso->certificado->cnpj }}" class="form-control" disabled>
    </div>

    <div class="form-group col-sm-3">
        <label for="cnpj">Data Expiração:</label>
        <input type="text" value="{{ !empty($acesso->certificado->data_validade) ?? $acesso->certificado->data_validade->format('d/m/Y') }}" class="form-control" disabled>
    </div>
</div>