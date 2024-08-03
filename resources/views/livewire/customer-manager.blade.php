<div>
    <button type="button" wire:click="create()" class="btn btn-primary">Tambah Pelanggan</button>
    @if ($isOpen)
        <div class="modal fade show" style="display: block;" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $customer_id ? 'Edit Pelanggan' : 'Tambah Pelanggan' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal()" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Pelanggan</label>
                                <input type="text" class="form-control" id="name" wire:model.defer="name"
                                    placeholder="Nama Pelanggan">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Pelanggan</label>
                                <input type="email" class="form-control" id="email" wire:model.defer="email"
                                    placeholder="Email">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telepon Pelanggan</label>
                                <input type="text" class="form-control" id="phone" wire:model.defer="phone"
                                    placeholder="Telepon">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat Pelanggan</label>
                                <textarea class="form-control" id="address" wire:model.defer="address" rows="3" placeholder="Alamat"></textarea>
                                @error('address')
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
                <th>Email</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->address }}</td>
                    <td>
                        <button wire:click="edit({{ $customer->id }})" class="btn btn-warning btn-sm">Edit</button>
                        <button wire:click="delete({{ $customer->id }})" class="btn btn-danger btn-sm">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
