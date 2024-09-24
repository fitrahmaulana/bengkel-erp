<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <strong>Pelanggan:</strong> {{ $invoice->customer->name }}
        </div>
        <div class="col-md-6">
            <strong>Tanggal Faktur:</strong> {{ $invoice->invoice_date }}
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Barang/Jasa</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoiceItems as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h5>Total: Rp{{ number_format($invoice->total_amount, 0, ',', '.') }}</h5>

    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary">Edit</a>
        </div>
    </div>
</div>
