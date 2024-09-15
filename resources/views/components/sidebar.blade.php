<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand"> <a href="./index.html" class="brand-link">
            <img src="{{ asset('adminlte/dist') }}/assets/img/AdminLTELogo.png" alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow">
            <span class="brand-text fw-light">Bengkel ERP</span> </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <x-nav-link href="{{ route('dashboard') }}" iconClass="bi bi-house" label="Dashboard" />
                <x-nav-link href="#" iconClass="bi bi-box" label="Manajemen Barang" :children="[
                    ['href' => route('items'), 'label' => 'Daftar Barang', 'routeName' => 'items'],
                    ['href' => route('suppliers'), 'label' => 'Suplier', 'routeName' => 'suppliers'],
                    ['href' => route('stock-movements'), 'label' => 'Pergerakan Stok', 'routeName' => 'stock-movements'],
                ]" routeName="items" />
                <x-nav-link href="{{ route('customers') }}" iconClass="bi bi-people" label="Manajemen Pelanggan" />
                <x-nav-link href="{{ route('sales-report') }}" iconClass="bi bi-graph-up" label="Laporan Penjualan" />
                <x-nav-link href="#" iconClass="bi bi-receipt" label="Manajemen Faktur" :children="[
                    ['href' => route('invoices.index'), 'label' => 'Daftar Faktur', 'routeName' => 'invoices.index'],
                    ['href' => route('invoices.create'), 'label' => 'Buat Faktur Baru', 'routeName' => 'invoices.create'],
                ]" />
            </ul>
        </nav>
    </div> <!--end::Sidebar Wrapper-->
</aside> <!--end::Sidebar--> <!--begin::App Main-->
