<div>
    <button type="button" wire:click="create()" class="btn btn-primary">Tambah Pergerakan Stok</button>
    @if($isOpen)
        <div class="modal fade show" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Pergerakan Stok</h5>
                        <button type="button" class="btn-close" wire:click="closeModal()"></button>
                    </div>
                    <div class="modal-body">
                        <select wire:model="item_id" class="form-control">
                            <option value="">Pilih Barang</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" wire:model="quantity" class="form-control mt-2" placeholder="Jumlah">
                        <select wire:model.change="type" class="form-control mt-2">
                            <option value="">Pilih Tipe</option>
                            <option value="in">Masuk</option>
                            <option value="out">Keluar</option>
                        </select>
                        @if($type == 'in')
                        <select wire:model="supplier_id" class="form-control mt-2">
                                <option value="">Pilih Pemasok</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        @endif
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
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tipe</th>
                <th>Pemasok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockMovements as $movement)
                <tr wire:key="movement-{{ $movement->id }}">
                    <td>{{ $movement->item->name }}</td>
                    <td>{{ $movement->quantity }}</td>
                    <td>{{ $movement->type }}</td>
                    <td>{{ $movement->supplier ? $movement->supplier->name : '-' }}</td>
                    <td>
                        <button wire:click="delete({{ $movement->id }})" class="btn btn-danger btn-sm">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
