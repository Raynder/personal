<x-report-layout>
    <div class="navbar-brand app-brand demo d-none d-xl-flex justify-content-between" style="padding: 20px;">
        <a href="#" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">
                <img src="{{ asset('img/logomarca.png') }}" alt="Logo Sistema" width="200" class="img-fluid" />
            </span>
            <div class="loader spinner" style="position: absolute;left: 225px;top: 25px;display:none">
                <i class="fas fa-circle-notch fa-spin text-primary fa-lg"></i>
            </div>
        </a>

        <button class="btn btn-primary btn-xs" onclick="window.print()">Imprimir</button>
    </div>
    <h4 class="breadcrumb-wrapper text-center mt-3">
        <div class="breadcrumb-title" style="font-size: 2rem;">Relação de Certificados</div>
    </h4>
    <div class="content" style="padding: 20px;">
        <table class="table ">
            <tr>
                <td style="width: 10%">
                    <b>Total de certificados:</b><br />
                    {{ $acessos->count() }}
                </td>
                <td>
                    <b>Empresa:</b><br />
                    {{ $acessos->first()->fantasia }}
                </td>
            </tr>
        </table>

        <table class="table " style="font-size: 0.7rem">
            <thead>
                <tr style="background-color: #e5e5e5">
                    <th>Item</th>
                    <th>Usuário do PC</th>
                    <th>Certificado</th>
                    <th>Razão Social</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($acessos as $obj)
                    <tr>
                        <th>{{ $loop->iteration }}</th>
                        <td>{{ $obj->nome_usuario ?? $obj->usuario }}</td>
                        <td>{{ $obj->cnpj ?? $obj->chave }}</td>
                        <td>{{ $obj->razao_social ?? 'Não identificado' }}</td>
                        <td>
                            @if ($obj->status == 'AA')
                                <div class="badge" style="padding: 3px 5px 3px 5px;">
                                    {{ App\Helpers\StatusAcessoHelper::getStatus($obj->status) }}
                                </div>
                            @elseif ($obj->status == 'AE')
                                <div class="badge"
                                    style="padding: 3px 5px 3px 5px; background-color: rgb(226, 148, 2);">
                                    {{ App\Helpers\StatusAcessoHelper::getStatus($obj->status) }}
                                </div>
                            @elseif ($obj->status == 'D')
                                <div class="badge" style="padding: 3px 5px 3px 5px; background-color: rgb(172, 0, 0);">
                                    {{ App\Helpers\StatusAcessoHelper::getStatus($obj->status) }}
                                </div>
                            @else
                                {{ App\Helpers\StatusAcessoHelper::getStatus($obj->status) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="table">
            <tr>
                <td>
                    <a href="https://app.bytoken.com.br" target="_blank" class="footer-link me-4">bytoken</a>
                    ©
                    <script>
                        document.write(new Date().getFullYear());
                    </script>

                </td>
                <td class="text-right" style="font-size: 0.6rem; ">
                    Emissão:
                    {{ Carbon\Carbon::now()->format('d/m/Y H:i') }}
                </td>
            </tr>
        </table>
    </div>
</x-report-layout>
