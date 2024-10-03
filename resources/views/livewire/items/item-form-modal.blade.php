<div>
    @if ($isOpen)
        <div class="modal fade show" style="display: block;" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $form->item_id ? 'Edit Item' : 'Tambah Item' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" id="name" wire:model.defer="form.name"
                                    placeholder="Contoh: Filter Udara Grand Avanza All New Rush Dual VVTI Original">
                                @error('form.name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi Barang</label>
                                <textarea class="form-control" id="description" wire:model.defer="form.description" rows="3"
                                    placeholder="Contoh: Filter udara untuk mobil Toyota Avanza, meningkatkan aliran udara dan efisiensi bahan bakar."></textarea>
                                @error('form.description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label">Jumlah Barang</label>
                                <input type="number" class="form-control" id="stock" wire:model.defer="form.stock"
                                    placeholder="Contoh: 20">
                                @error('form.stock')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga Barang</label>
                                <input type="number" class="form-control" id="price" wire:model.defer="form.price"
                                    placeholder="Contoh: 75000">
                                @error('form.price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="min_stock" class="form-label">Stok Minimum</label>
                                <input type="number" class="form-control" id="min_stock"
                                    wire:model.defer="form.min_stock" placeholder="Contoh: 5">
                                @error('form.min_stock')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="supplier_id" class="form-label
                                    {{ $form->item_id ? 'd-none' : '' }}">Supplier</label>
                                <select class="form-select" id="supplier_id" wire:model.defer="form.supplier_id"
                                    {{ $form->item_id ? 'disabled' : '' }}>
                                    <option value="">Pilih Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                @error('form.supplier_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Tutup</button>
                        <button type="button" class="btn btn-primary" wire:click="save">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
