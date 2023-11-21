@include('adminlte-templates::common.errors')
<div class="row">
    <div class="nav-align-top">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                    data-bs-target="#navs-top-atual" aria-controls="navs-top-atual" aria-selected="true">
                    Acesso Atual
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-certificados"
                    aria-controls="navs-top-certificados" aria-selected="false">
                    Certificados ByToken
                    <span class="badge-items " style="float: right; margin-left: 12px; width: 23px;">
                        {{ $certificadosFlyToken->count() }}
                    </span>
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-outros"
                    aria-controls="navs-top-outros" aria-selected="false">
                    Outros Certificados
                    <span class="badge-items " style="float: right; margin-left: 12px; width: 23px;">
                        {{ $outrosCertificados->count() }}
                    </span>
                </button>
            </li>
        </ul>
        <div class="tab-content" style="padding: 0 !important;">
            <div class="tab-pane active" id="navs-top-atual" style="padding: 1.375rem !important;" role="tabpanel">
                @include('acessos.fields')
            </div>
            <div class="tab-pane" id="navs-top-certificados" role="tabpanel"  style="padding: 0 !important;">
                @include('acessos.certificados')
            </div>
            <div class="tab-pane" id="navs-top-outros" role="tabpanel"  style="padding: 0 !important;">
                @include('acessos.outros')
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        registerTooltip();
    });
</script>
