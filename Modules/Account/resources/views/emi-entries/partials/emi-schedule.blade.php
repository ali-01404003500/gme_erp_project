<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th>SL</th>
            <th>Date</th>
            <th>Status</th>
            <th>Payment Date</th>
            <th>Installment</th>
            <th>Paid Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
            $earlySettlementCount = $emiEntry->emiDetails->where('status', 'early_settlement_paid')->count();
            $earlySettlementShown = false;
        @endphp

        @foreach ($emiEntry->emiDetails as $key => $detail)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($detail->emi_date)->format('d-M-Y') }}</td>
                <td>
                    @if ($detail->status == 'due')
                        <span class="badge badge-round bg-primary">Due</span>
                    @elseif ($detail->status == 'processing')
                        <span class="badge badge-round bg-info">Processing</span>
                    @elseif ($detail->status == 'settlement_processing')
                        <span class="badge badge-round bg-warning">Settlement Processing</span>
                    @elseif ($detail->status == 'rescheduled')
                        <span class="badge badge-round bg-secondary">Rescheduled</span>
                    @elseif ($detail->status == 'early_settlement_paid')
                        <span class="badge badge-round bg-info">Early Settlement Paid</span>
                    @else
                        <span class="badge badge-round bg-success">Paid</span>
                    @endif
                </td>
                <td>
                    @if (in_array($detail->status, ['paid','early_settlement_paid']))
                        {{ $detail->updated_at->format('d-M-Y') }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ number_format($detail->emi_amount) }}</td>
                <td>
                    @if (in_array($detail->status, ['paid','early_settlement_paid']))
                        {{ number_format($detail->emi_amount) }}
                    @else
                        0.00
                    @endif
                </td>

                {{-- Action column --}}
                @if ($detail->status == 'early_settlement_paid')
                    {{-- Show one merged Money Receipt cell for all early settlement rows --}}
                    @if (!$earlySettlementShown)
                        <td rowspan="{{ $earlySettlementCount }}">
                            <a href="{{ route('account.emi-collections.showMoneyReceipt', $emiEntry->id) }}?emientry_id={{ $emiEntry->id }}" 
                               target="_blank" class="btn btn-info btn-sm">
                                Money Receipt
                            </a>
                        </td>
                        @php $earlySettlementShown = true; @endphp
                    @endif
                @else
                    <td>
                        @if ($detail->status == 'due')
                            <button type="button" class="btn btn-success btn-sm make-collection-btn" 
                                data-bs-toggle="modal"
                                data-bs-target="#emiCollectionModal" 
                                data-emi-detail-id="{{ $detail->id }}">
                                Make Collection
                            </button>
                        @elseif ($detail->status == 'processing')
                            <button type="button" class="btn btn-danger btn-sm delete-emi-detail-btn"
                                data-emi-detail-id="{{ $detail->id }}">
                                Rollback
                            </button>
                        @elseif ($detail->status == 'paid')
                            <a href="{{ route('account.emi-collections.showMoneyReceipt', $detail->id)}}?emientrydetail_id={{ $detail->id }}" 
                               target="_blank" class="btn btn-info btn-sm">
                                Money Receipt
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
