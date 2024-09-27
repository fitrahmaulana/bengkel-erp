<div class="container">
    <form wire:submit='save'>
        <!-- Customer selection and date input -->
        <div class="row mb-3">
            <div class="col-md-6">
                <select wire:model.live="form.customer_id" class="form-control">
                    <option value="">Pilih Pelanggan</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
                @error('form.customer_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <input type="date" wire:model.live="form.invoice_date" class="form-control">
                @error('form.invoice_date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Search bar for items -->
        <div class="row mb-3">
            <div class="col-md-12">
                <input type="text" wire:model.live="searchTerm" class="form-control" placeholder="Cari Barang...">
                @if (!empty($items))
                    <ul class="list-group mt-2">
                        @foreach ($items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                wire:click="selectItem({{ $item->id }})">
                                {{ $item->name }} (Stok: {{ $item->stock }}) -
                                Rp{{ number_format($item->price, 0, ',', '.') }}
                                <button class="btn btn-sm btn-primary">Pilih</button>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Custom item section -->
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" wire:model.live="customItemName" class="form-control"
                    placeholder="Nama Jasa/Barang Custom">
                @error('customItemName')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-3">
                <select wire:model.live="customItemType" class="form-control">
                    <option value="service">Jasa</option>
                    <option value="custom">Custom</option>
                </select>
                @error('customItemType')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-1">
                <input type="number" wire:model.live="customItemQuantity" class="form-control" placeholder="Qty">
                @error('customItemQuantity')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-2">
                <input type="text" wire:model.live="customItemPrice" class="form-control" placeholder="Harga">
                @error('customItemPrice')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-primary" wire:click="addCustomItem">Tambah</button>
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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- invoice item row --}}
                @if (!empty($form->invoiceItems || $form->customItems))
                    @foreach ($form->invoiceItems as $index => $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>
                                <input type="number"
                                    wire:model.live.debounce500ms="form.invoiceItems.{{ $index }}.quantity"
                                    class="form-control">
                                @error('form.invoiceItems.' . $index . '.quantity')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($item['total'], 0, ',', '.') }}</td>
                            <td><button type="button" class="btn btn-danger btn-sm"
                                    wire:click="removeInvoiceItem({{ $index }})">Hapus</button></td>
                        </tr>
                    @endforeach

                    @if (!empty($form->customItems))
                        <tr>
                            <td colspan="5" class="text-center">Jasa/Barang Custom</td>
                        </tr>
                    @endif

                    {{-- custom item row --}}
                    @foreach ($form->customItems as $index => $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>
                                <input type="number"
                                    wire:model.live.debounce500ms="form.customItems.{{ $index }}.quantity"
                                    class="form-control">
                            </td>
                            <td>Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($item['total'], 0, ',', '.') }}</td>
                            <td><button type="button" class="btn btn-danger btn-sm"
                                    wire:click="removeCustomItem({{ $index }})">Hapus</button></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">Belum ada barang/jasa yang ditambahkan</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <h5>Total: Rp{{ number_format($form->total_amount, 0, ',', '.') }}</h5>

        <div class="row">
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-success">Simpan Faktur</button>
            </div>
        </div>
    </form>
</div>
