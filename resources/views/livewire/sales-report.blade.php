<div>
    <div class="row mb-3">
        <div class="col-md-5">
            <input type="date" wire:model="start_date" class="form-control" placeholder="Tanggal Mulai">
        </div>
        <div class="col-md-5">
            <input type="date" wire:model="end_date" class="form-control" placeholder="Tanggal Selesai">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-primary" wire:click="render()">Cari</button>
        </div>
    </div>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Pelanggan</th>
                <th>Tanggal Faktur</th>
                <th>Jumlah Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr wire:key="invoice-{{ $invoice->id }}">
                    <td>{{ $invoice->customer->name }}</td>
                    <td>{{ $invoice->invoice_date }}</td>
                    <td>{{ $invoice->total_amount }}</td>
                    <td>{{ $invoice->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
