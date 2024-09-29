<tr>
    <td>{{ $item['name'] }}</td>
    <td>
        <input type="number"
               wire:model.live="quantity"
               class="form-control"
               min="1"
               max="{{ $stock }}">
        @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
    </td>
    <td>Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
    <td>Rp{{ number_format($item['total'], 0, ',', '.') }}</td>
    <td>
        <button type="button" class="btn btn-danger btn-sm" wire:click="remove">Remove</button>
    </td>
</tr>
