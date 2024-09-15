<!-- resources/views/livewire/item-manager.blade.php -->

<div>
    <button type="button" wire:click="create()" class="btn btn-primary">Tambah Barang</button>

    @if ($isOpen)
        <div class="modal fade show" style="display: block;" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $item_id ? 'Edit Item' : 'Tambah Item' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal()" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" id="name" wire:model.defer="name"
                                    placeholder="Contoh: Filter Udara Grand Avanza All New Rush Dual VVTI Original">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi Barang</label>
                                <textarea class="form-control" id="description" wire:model.defer="description" rows="3"
                                    placeholder="Contoh: Filter udara untuk mobil Toyota Avanza, meningkatkan aliran udara dan efisiensi bahan bakar."></textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label">Jumlah Barang</label>
                                <input type="number" class="form-control" id="stock" wire:model.defer="stock"
                                    placeholder="Contoh: 20">
                                @error('stock')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga Barang</label>
                                <input type="number" class="form-control" id="price" wire:model.defer="price"
                                    placeholder="Contoh: 75000">
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="min_stock" class="form-label">Stok Minimum</label>
                                <input type="number" class="form-control" id="min_stock" wire:model.defer="min_stock"
                                    placeholder="Contoh: 5">
                                @error('min_stock')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </form>
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
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Stok</th>
                <th>Harga</th>
                <th>Stok Minimum</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr wire:key="item-{{ $item->id }}">
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->stock }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->min_stock }}</td>
                    <td>
                        <button wire:click="edit({{ $item->id }})" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button wire:click="delete({{ $item->id }})"
                            wire:confirm="Apakah Anda yakin ingin menghapus item ini?"
                            class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
