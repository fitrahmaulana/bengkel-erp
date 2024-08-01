<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand"> <a href="./index.html" class="brand-link">
            <img src="{{ asset('adminlte/dist') }}/assets/img/AdminLTELogo.png" alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow">
            <span class="brand-text fw-light">Bengkel ERP</span> </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <x-navlink
                    href="#"
                    iconClass="bi bi-speedometer"
                    label="Dashboard"
                    :children="[
                        ['href' => './index.html', 'label' => 'Dashboard v1'],
                        ['href' => './index2.html', 'label' => 'Dashboard v2'],
                        ['href' => './index3.html', 'label' => 'Dashboard v3']
                    ]"
                />
                <x-navlink
                    href="inventory"
                    iconClass="bi bi-box"
                    label="Manajemen Barang"
                />
            </ul>
        </nav>
    </div> <!--end::Sidebar Wrapper-->
</aside> <!--end::Sidebar--> <!--begin::App Main-->
