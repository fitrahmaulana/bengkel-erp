<!-- resources/views/livewire/dashboard-component.blade.php -->
<div class="container py-4">
    <div class="row g-4">
        <!-- Statistik Faktur -->
        <div class="col-md-4">
            <div class="card text-bg-primary h-100">
                <div class="card-header">
                    <i class="bi bi-receipt-cutoff me-2"></i>Statistik Faktur
                </div>
                <div class="card-body">
                    <h5 class="card-title">Total Faktur: {{ $totalInvoices }}</h5><br>
                    <p class="card-text">Total Penjualan: Rp{{ number_format($totalSales, 0, ',', '.') }}</p>
                    <p class="card-text">Faktur Belum Dibayar: {{ $unpaidInvoices }}</p>
                </div>
            </div>
        </div>

        <!-- Statistik Pelanggan -->
        <div class="col-md-4">
            <div class="card text-bg-success h-100">
                <div class="card-header">
                    <i class="bi bi-people me-2"></i>Statistik Pelanggan
                </div>
                <div class="card-body">
                    <h5 class="card-title">Total Pelanggan: {{ $totalCustomers }}</h5><br>
                    <p class="card-text">Pelanggan Baru (30 hari): {{ $newCustomers }}</p>
                </div>
            </div>
        </div>

        <!-- Statistik Inventaris -->
        <div class="col-md-4">
            <div class="card text-bg-danger h-100">
                <div class="card-header">
                    <i class="bi bi-box-seam me-2"></i>Statistik Inventaris
                </div>
                <div class="card-body">
                    <h5 class="card-title">Barang dengan Stok Rendah: {{ $lowStockItems }}</h5><br>
                    <p class="card-text">Total Item: {{ $totalItems }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
