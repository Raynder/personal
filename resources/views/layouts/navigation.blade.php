<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
    <div class="container-xxl d-flex h-100">
        <ul class="menu-inner">
            <!-- Dashboards -->
            <li class="menu-item">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-bar-chart-square"></i>
                    <div data-i18n="Dashboards">Dashboard</div>
                </a>
            </li>
            @if (session()->has('empresa_id'))
                <li class="menu-item">
                    <a href="{{ route('acessos.index') }}" class="menu-link {{ Request::is('acessos*') ? 'active' : '' }}">
                        <i class="menu-icon tf-icons bx bxs-user-account"></i>
                        <div>Acessos</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="https://www.flybisistemas.com.br/dw/FlyTokenSetup.exe" target="_blank" class="menu-link">
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
            @endif
        </ul>
    </div>
</aside>
