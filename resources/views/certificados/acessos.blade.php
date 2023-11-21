    <table class="table table-hover">
        <thead class="table-header">
            <tr class="sticky-top">
                <th>Empresa</th>
                <th>Status</th>
                <th>Usuário</th>
                <th>Token PC</th>
            </tr>
        </thead>
        <tbody>
            @forelse($acessos as $obj)
                <tr id="row_{{ $obj->id }}" class="@if ($obj->deleted_at != null) table-danger @endif">
                    <td>
                        @if ($obj->certificado)
                            {{ App\Helpers\FormatterHelper::formatCnpjCpf($obj->certificado->cnpj) }} |
                            {{ $obj->certificado->razao_social }}
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('acessos.status', [$obj->id]) }}" method="GET"
                            id="form_{{ $obj->id }}" class="form-inline">
                            @csrf

                            <button type="button" style="border: none; background: none;"
                                class="btn btn-sm btn-secondary"
                                onclick="Tela.abrirJanela('{{ route('acessos.opcoes', [$obj->id]) }}', 'Atualizar Acesso', 'xs')">
                                <i class="{{ App\Helpers\StatusAcessoHelper::getIcone($obj->status) }}"
                                    data-bs-toggle="tooltip" data-placement="top" data-color="secondary"
                                    data-bs-original-title="{{ App\Helpers\StatusAcessoHelper::getStatus($obj->status) }}"
                                    title="{{ App\Helpers\StatusAcessoHelper::getStatus($obj->status) }}"></i>
                            </button>
                        </form>
                    </td>
                    <td>{{ $obj->usuario }}</td>
                    <td>{{ $obj->uuid_usuario }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Nenhum usuário encontrado</td>
                </tr>
            @endforelse
        </tbody>
    </table>
