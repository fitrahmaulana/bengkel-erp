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
                    <h5 class="card-title">Barang dengan Stok Rendah: {{ $lowStockItems->count() }}</h5><br>
                    <p class="card-text">Total Item: {{ $totalItems }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel lowStockItems -->
    <div class="card mt-4">
        <div class="card-header">
            <i class="bi bi-box-seam me-2"></i>Barang dengan Stok Rendah
        </div>
        <div class="card-body">
            <table class="table  table-bordered table-striped">
                <thead>
                    <tr class="table-success">
                        <th>Nama Barang</th>
                        <th>Minimal Stok</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lowStockItems as $item)
                        <tr class="{{ $item->stock < $item->min_stock ? 'table-danger' : '' }}">
                            <td>
                                {{ $item->name }}
                                @if ($item->stock < $item->min_stock)
                                    <div class="text-danger">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                        Peringatan: Stok Rendah!
                                    </div>
                                @endif
                            </td>
                            <td>{{ $item->min_stock }}</td>
                            <td>{{ $item->stock }}</td>
                            <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                            {{-- munculkan supplier dengan modal yang berisi supplier dan nomor telepon --}}
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#supplierModal">Pesan</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- munculkan supplier dengan modal yang berisi supplier dan nomor telepon --}}
    <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplierModalLabel">Pesan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body
                ">
                    <p>Nama Supplier: {{ $item->supplier->name }}</p>
                    <p>Sales: {{$item->supplier->contact_person}} </p>
                    <p>Nomor Telepon: {{ $item->supplier->phone }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
