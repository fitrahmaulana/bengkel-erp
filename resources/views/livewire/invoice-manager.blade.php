<div>
    <button type="button" wire:click="create()" class="btn btn-primary">Tambah Faktur</button>
    @if($isOpen)
        <div class="modal fade show" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $invoice_id ? 'Edit Faktur' : 'Tambah Faktur' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal()"></button>
                    </div>
                    <div class="modal-body">
                        <select wire:model="customer_id" class="form-control">
                            <option value="">Pilih Pelanggan</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        <input type="date" wire:model="invoice_date" class="form-control mt-2">
                        <input type="number" wire:model="total_amount" class="form-control mt-2" placeholder="Jumlah Total">
                        <select wire:model="status" class="form-control mt-2">
                            <option value="unpaid">Belum Dibayar</option>
                            <option value="paid">Dibayar</option>
                        </select>
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
                <th>Jumlah Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr wire:key="invoice-{{ $invoice->id }}">
                    <td>{{ $invoice->customer->name }}</td>
                    <td>{{ $invoice->invoice_date }}</td>
                    <td>{{ $invoice->total_amount }}</td>
                    <td>{{ $invoice->status }}</td>
                    <td>
                        <button wire:click="edit({{ $invoice->id }})" class="btn btn-warning btn-sm">Edit</button>
                        <button wire:click="delete({{ $invoice->id }})" class="btn btn-danger btn-sm">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
