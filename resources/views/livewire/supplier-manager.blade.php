<div>
    <button wire:click="create()">Tambah Pemasok</button>
    @if($isOpen)
    <div class="modal">
        <div class="modal-content">
            <span wire:click="closeModal()">×</span>
            <h2>{{ $supplier_id ? 'Edit Pemasok' : 'Tambah Pemasok' }}</h2>
            <input type="text" wire:model="name" placeholder="Nama Pemasok">
            <textarea wire:model="contact_info" placeholder="Informasi Kontak"></textarea>
            <button wire:click="store()">Simpan</button>
        </div>
    </div>
    @endif
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kontak</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $supplier)
            <tr>
                <td>{{ $supplier->name }}</td>
                <td>{{ $supplier->contact_info }}</td>
                <td>
                    <button wire:click="edit({{ $supplier->id }})">Edit</button>
                    <button wire:click="delete({{ $supplier->id }})">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
