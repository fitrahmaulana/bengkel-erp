<div>
    <a href="{{route('invoices.create')}}" class="btn btn-primary">Tambah Faktur</a>

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
                        <a href="{{route('invoices.show', $invoice->id)}}" class="btn btn-secondary btn-sm">Detail</a>
                        <a href="{{route('invoices.edit', $invoice->id)}}" class="btn btn-primary btn-sm">Edit</a>
                        <button wire:click="delete({{ $invoice->id }})" class="btn btn-danger btn-sm">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
