<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-header">
            <tr class="sticky-top">
                <th>Empresa</th>
                <th>Status</th>
                <th>Usuario</th>
                <th class="text-center">Data de envio</th>
                <th class="text-center">Data de instalação</th>
                <th class="text-center">Meio de envio</th>
                <!-- <th class="text-center">Opções</th> -->
            </tr>
        </thead>
        <tbody>
            @forelse($acessos as $obj)
                <tr id="row_{{ $obj->id }}" class="@if ($obj->deleted_at != null) table-danger @endif">
                    <td>{{ App\Helpers\FormatterHelper::formatCnpjCpf($obj->certificado->cnpj) }} |
                        {{ $obj->certificado->razao_social }}</td>
                    <td>
                        <form action="{{ route('acessos.status', [$obj->id]) }}" method="GET" id="form_{{ $obj->id }}"
                            class="form-inline">
                            @csrf
    
                            <button type="button" style="border: none; background: none;" class="btn btn-sm btn-secondary" onclick="Tela.abrirJanela('{{ route('acessos.opcoes', [$obj->id]) }}', 'Atualizar Acesso', 'xs')">
                                <i class="{{ App\Helpers\StatusAcessoHelper::getIcone($obj->status) }}"
                                        data-bs-toggle="tooltip" data-placement="top" data-color="secondary"
                                        data-bs-original-title="{{ App\Helpers\StatusAcessoHelper::getStatus($obj->status) }}"
                                        title="{{ App\Helpers\StatusAcessoHelper::getStatus($obj->status) }}"></i>
                            </button>
                        </form>
                    </td>
                    <td>{{ $obj->usuario }}</td>
                    <td class="text-center">{{ $obj->created_at->format('d/m/Y H:m') }}</td>
                    <td class="text-center">{{ ($obj->updated_at != $obj->created_at) ? $obj->updated_at->format('d/m/Y H:m') : '-' }}</td>
                    <td class="text-center"> - </td>
                    <!-- <td width="150" class="text-center">
                        <div class='btn-group'>
                            <a href="javascript:void(0);"
                                onclick="Tela.abrirJanela('{{ route('acessos.show', [$obj->id]) }}', 'Visualizar Acesso do Usuário', 'lg')" class="btn-table btn-xs">
                                <i class="fa fa-eye"></i>
                            </a>
                        </div>
                    </td> -->
                </tr>
            @empty
                <tr>
                    <td colspan="4">Nenhum acesso encontrado</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<div class="card-footer clearfix">
    <div class="float-right">
        @include('layouts.pagination', [
            'paginator' => $acessos,
            'filtro' => '&' . http_build_query(request()->except('page')),
        ])
    </div>
</div>
<script>
    $(function() {
        registerTooltip();
    });
</script>
