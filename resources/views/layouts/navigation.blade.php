<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
    <div class="container-xxl d-flex h-100">
        <ul class="menu-inner d-flex justify-content-center">
            <!-- Dashboards -->
            <li class="menu-item">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-bar-chart-square"></i>
                    <div data-i18n="Dashboards">Dashboard</div>
                </a>
            </li>
            @if (session()->has('empresa_id'))
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-bar-chart-square"></i>
                        <div data-i18n="Controle">Controle</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="{{ route('alunos.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bxs-group"></i>
                                <div>Alunos</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('acessos.index') }}"
                                class="menu-link {{ Request::is('acessos*') ? 'active' : '' }}">
                                <i class="menu-icon tf-icons bx bxs-user-account"></i>
                                <div>Logs</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('exercicios.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bxs-user"></i>
                                <div>Exercicios</div>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Cadastros">Cadastros</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="{{ route('treinos.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bxs-user-account"></i>
                                <div>Treinos</div>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-file"></i>
                        <div data-i18n="Relatórios">Relatórios</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="{{ route('relatorios.certificados') }}" target="_blank" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-key"></i>
                                <div>Certificados</div>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item">
                    <a href="https://www.bytoken.com.br/dw/ByTokenSetup.exe" target="_blank" class="menu-link">
                        <i class="menu-icon tf-icons fa fa-external-link-alt"></i>
                        <div>Baixar App</div>
                    </a>
                </li>
            @else
                <li class="menu-item">
                    <a href="{{ route('empresas.index') }}"
                        class="menu-link {{ Request::is('empresas*') ? 'active' : '' }}">
                        <i class="menu-icon tf-icons bx bxs-city"></i>
                        <div>Empresas</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('certificadoras.index') }}"
                        class="menu-link {{ Request::is('certificadoras*') ? 'active' : '' }}">
                        <i class="menu-icon tf-icons bx bxs-check-shield"></i>
                        <div>Certificadoras</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('novidades.index') }}"
                        class="menu-link {{ Request::is('novidades*') ? 'active' : '' }}">
                        <i class="menu-icon tf-icons bx bxs-bell"></i>
                        <div>Novidades</div>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</aside>
