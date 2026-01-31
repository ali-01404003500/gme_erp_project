<div class="serial-info-container">
    <div class="mb-3">
        <h6 class="mb-2"><strong>Lot No:</strong> {{ $lotNo }}</h6>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-bordered table-hover">
            <thead class="bg-info text-white">
                <tr>
                    <th style="width: 10%;">SL</th>
                    <th style="width: 40%;">Serial Number</th>
                    <th class="text-center" style="width: 20%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($serialData as $index => $serial)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <code class="bg-light p-1">{{ $serial->serial_no }}</code>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($serial->manufacture_date)->format('d-M-Y') }}
                        </td>
                        <td class="text-center">
                            @php
                                $statusMap = [
                                    'available' => ['class' => 'success', 'text' => 'Available'],
                                    'sold' => ['class' => 'danger', 'text' => 'Sold'],
                                    'reserved' => ['class' => 'warning', 'text' => 'Reserved'],
                                    'damaged' => ['class' => 'dark', 'text' => 'Damaged'],
                                ];
                                $status = $statusMap[$serial->status] ?? ['class' => 'secondary', 'text' => 'Unknown'];
                            @endphp
                            <span class="badge badge-{{ $status['class'] }}">
                                {{ $status['text'] }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <i class="las la-barcode" style="font-size: 36px; color: #ddd;"></i>
                            <p class="mb-0">No serial numbers found for this lot</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($serialData->count() > 0)
            <tfoot class="bg-light font-weight-bold">
                <tr>
                    <td colspan="3" class="text-right"><strong>Total Serials:</strong></td>
                    <td class="text-center">
                        <span class="badge badge-primary p-2">{{ $totalStock }}</span>
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    @if($serialData->count() > 0)
    <div class="row mt-3">
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center p-2">
                    <small class="text-success">Available</small>
                    <h5>{{ $serialData->where('status', 'available')->count() }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body text-center p-2">
                    <small class="text-danger">Sold</small>
                    <h5>{{ $serialData->where('status', 'sold')->count() }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center p-2">
                    <small class="text-warning">Reserved</small>
                    <h5>{{ $serialData->where('status', 'reserved')->count() }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-dark">
                <div class="card-body text-center p-2">
                    <small class="text-dark">Damaged</small>
                    <h5>{{ $serialData->where('status', 'damaged')->count() }}</h5>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .serial-info-container .table td,
    .serial-info-container .table th {
        padding: 8px;
        vertical-align: middle;
        font-size: 13px;
    }
    
    code {
        font-size: 12px;
    }
</style>