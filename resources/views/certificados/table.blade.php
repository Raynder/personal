<table class="table table-responsive table-hover">
    <thead class="table-dark">
        <tr>
            <th>CNPJ</th>
            {!! App\Helpers\TableHelper::sortable_column('fantasia', 'Nome Fantasia') !!}
            <th>DATA VALIDADE</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tbody>
        @forelse($certificados as $obj)
            <tr id="row_{{ $obj->id }}" class="@if ($obj->deleted_at != null) table-danger @endif">
                <td>{{ App\Helpers\FormatterHelper::formatCnpjCpf($obj->cnpj) }}</td>
                <td>{{ $obj->fantasia }}</td>
                <td>{{ $obj->validade }}</td>
                <td width="120">
                    <div class='btn-group'>
                        <a href="javascript:void(0);"
                            onclick="Tela.abrirJanela('{{ route('certificado.createChave', [$obj->id]) }}', 'Chave de Acesso:', 'xs')" class="btn btn-primary btn-xs">
                            <i class="fas fa-key"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td>Nenhum registro encontrado</td>
            </tr>
        @endforelse
    </tbody>
</table>
