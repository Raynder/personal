<div class="table-responsive" style="margin: 0 !important;">
    <table class="table table-hover">
        <thead class="table-header">
            <tr class="sticky-top">
                <th>CNPJ</th>
                <th>Razão Social</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($certificadosFlyToken as $obj)
                <tr id="row_{{ $obj[0]->id }}" class="@if ($obj[0]->deleted_at != null) table-danger @endif">
                    <td>{{ App\Helpers\FormatterHelper::formatCnpjCpf($obj[0]->certificado->cnpj) }}</td>
                    <td>{{ $obj[0]->certificado->razao_social }}</td>
                    <td>
                        <form action="{{ route('acessos.status', [$obj[0]->id]) }}" method="GET" id="form_{{ $obj[0]->id }}"
                            class="form-inline">
                            @csrf
    
                            <button type="button" style="border: none; background: none; box-shadow: none;" class="btn btn-sm btn-secondary" onclick="Tela.abrirJanela('{{ route('acessos.opcoes', [$obj[0]->id]) }}', 'Acesso: {{ $obj[0]->certificado->razao_social }}', 'xs')">
                                <i class="{{ App\Helpers\StatusAcessoHelper::getIcone($obj[0]->status) }}"
                                        data-bs-toggle="tooltip" data-placement="top" data-color="secondary"
                                        data-bs-original-title="{{ App\Helpers\StatusAcessoHelper::getStatus($obj[0]->status) }}"
                                        title="{{ App\Helpers\StatusAcessoHelper::getStatus($obj[0]->status) }}"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Nenhum usuário encontrado</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>