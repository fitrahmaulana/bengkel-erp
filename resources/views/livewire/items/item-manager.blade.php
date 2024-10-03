<!-- resources/views/livewire/item-manager.blade.php -->

<div>
    <button type="button" wire:click="create()" class="btn btn-primary">Tambah Barang</button>

    <livewire:items.item-form-modal />

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Stok</th>
                <th>Harga</th>
                <th>Stok Minimum</th>
                <th>Supplier</th>
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
                    <td>{{ $item->supplier->name }}</td>
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
