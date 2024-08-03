<div>
    <button type="button" wire:click="create()" class="btn btn-primary">Tambah Pemasok</button>
    @if($isOpen)
        <div class="modal fade show" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $supplier_id ? 'Edit Pemasok' : 'Tambah Pemasok' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal()"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" wire:model="name" class="form-control" placeholder="Nama">
                        <input type="text" wire:model="contact_person" class="form-control mt-2" placeholder="Contact Person">
                        <input type="text" wire:model="phone" class="form-control mt-2" placeholder="Telepon">
                        <textarea wire:model="address" class="form-control mt-2" placeholder="Alamat"></textarea>
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
                <th>Contact Person</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $supplier)
                <tr wire:key="supplier-{{ $supplier->id }}">
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->contact_person }}</td>
                    <td>{{ $supplier->phone }}</td>
                    <td>{{ $supplier->address }}</td>
                    <td>
                        <button wire:click="edit({{ $supplier->id }})" class="btn btn-warning btn-sm">Edit</button>
                        <button wire:click="delete({{ $supplier->id }})" class="btn btn-danger btn-sm">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
