<!-- resources/views/livewire/invoice-manager.blade.php -->
<div>
    <button type="button" wire:click="create()" class="btn btn-primary">Tambah Faktur</button>

    @if ($isOpen)
        <div class="modal fade show" style="display: block;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Faktur</h5>
                        <button type="button" class="btn-close" wire:click="closeModal()"></button>
                    </div>
                    <div class="modal-body">
                        <select wire:model="customer_id" class="form-control">
                            <option value="">Pilih Pelanggan</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <input type="date" wire:model="invoice_date" class="form-control mt-2">
                        @error('invoice_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <!-- Search bar for items -->
                        <input type="text" wire:model.live="searchTerm" class="form-control mt-3"
                            placeholder="Cari Barang...">

                        @if (!empty($items))
                            <ul class="list-group mt-2">
                                @foreach ($items as $item)
                                    <li class="list-group-item" wire:click="selectItem({{ $item->id }})">
                                        {{ $item->name }} - Rp{{ number_format($item->price, 0, ',', '.') }} (Stok:
                                        {{ $item->quantity }})
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <hr>
                        <div class="row align-items-center mb-2">
                            <div class="col-md-5">
                                <label>Nama Barang</label>
                            </div>
                            <div class="col-md-2">
                                <label>Qty</label>
                            </div>
                            <div class="col-md-2">
                                <label>@</label>
                            </div>
                            <div class="col-md-2">
                                <label>Jumlah</label>
                            </div>
                            <div class="col-md-1">
                                <!-- Placeholder for alignment -->
                            </div>
                        </div>

                        <!-- List of selected items -->
                        @foreach ($invoiceItems as $index => $invoiceItem)
                            <div class="row align-items-center">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" value="{{ $invoiceItem['name'] }}"
                                        readonly>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" wire:model.live.debounce500ms="invoiceItems.{{ $index }}.quantity" class="form-control"
                                        placeholder="Jumlah">
                                    @error('invoiceItems.' . $index . '.quantity')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control"
                                        value="Rp{{ number_format($invoiceItem['price'], 0, ',', '.') }}" readonly>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control"
                                        value="Rp{{ number_format($invoiceItem['total'], 0, ',', '.') }}" readonly>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        wire:click="removeInvoiceItem({{ $index }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <hr>
                        @endforeach

                        <h5>Total: Rp{{ number_format($total_amount, 0, ',', '.') }}</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal()">Tutup</button>
                        <button type="button" class="btn btn-primary" wire:click="store()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Pelanggan</th>
                <th>Tanggal Faktur</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $invoice)
                <tr wire:key="invoice-{{ $invoice->id }}">
                    <td>{{ $invoice->customer->name }}</td>
                    <td>{{ $invoice->invoice_date }}</td>
                    <td>Rp{{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                    <td>{{ $invoice->status }}</td>
                    <td>
                        <button wire:click="print({{ $invoice->id }})" class="btn btn-info btn-sm">Cetak</button>
                        <button wire:click="delete({{ $invoice->id }})" class="btn btn-danger btn-sm">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>