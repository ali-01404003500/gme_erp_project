{{-- resources/views/crm/daily-credit-call/show-modal.blade.php --}}
 
<div class="row mb-4">
    <div class="col-md-12">
        <div class="col-md-12 " style='font-size:25px'>
            {{$customer->company_name.' - '.$customer->address}}
        </div>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th style="width: 5%;">SL</th>
                    <th style="width: 15%;">Reminder Date</th>
                    <th style="width: 15%;">Commitment Date</th>
                    <th style="width: 15%;">Commitment Amount</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 40%;">Call Info</th>
                </tr>
            </thead>
            <tbody> 
                @php
                
                    $entries = $dailyCreditCall->original['data'] ?? [];
                @endphp
                @if(!empty($entries) && count($entries) > 0)
                    @foreach($entries as $index => $entry)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $entry['call_date'] ?? '-' }}</td>
                            <td>{{ $entry['commitment_date'] ?? '-' }}</td>
                            <td>{{ $entry['commitment_amount'] ?? 0 }}</td>
                            <td>{{ $entry['status'] ?? '-' }}</td>
                            <td>
                                {{  $entry['create_by']['full_name']  ?? '-' }}
                                <br>
                                {{ $entry['created_at'] ? substr($entry['created_at'], 0, 10) : '-' }}
                            </td> 
                        </tr>
                        <tr> 
                            <td colspan="6">{{ $entry['remarks'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">No records found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>