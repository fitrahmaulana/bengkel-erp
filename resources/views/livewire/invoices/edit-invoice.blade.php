<div class="container">
    <form wire:submit.prevent="update">
        <!-- Customer selection and date input -->
        <div class="row mb-3">
            <div class="col-md-6">
                <select wire:model="customer_id" class="form-control">
                    <option value="">Pilih Pelanggan</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
                @error('customer_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <input type="date" wire:model="invoice_date" class="form-control">
                @error('invoice_date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Invoice items table -->
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
                @foreach ($invoiceItems as $index => $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>
                            <input type="number" wire:model="invoiceItems.{{ $index }}.quantity" class="form-control">
                            @error('invoiceItems.' . $index . '.quantity')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </td>
                        <td>Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($item['total'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h5>Total: Rp{{ number_format($total_amount, 0, ',', '.') }}</h5>

        <div class="row">
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-success">Perbarui Faktur</button>
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>
