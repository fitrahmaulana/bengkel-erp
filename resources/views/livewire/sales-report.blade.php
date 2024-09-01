<!-- resources/views/livewire/sales-report.blade.php -->
<div>
    <div class="row">
        <div class="col-md-6">
            <input type="date" wire:model="startDate" class="form-control">
        </div>
        <div class="col-md-6">
            <input type="date" wire:model="endDate" class="form-control">
        </div>
    </div>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesData as $data)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($data->date)->format('d/m/Y') }}</td>
                    <td>{{ "Rp " . number_format($data->total_sales, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
