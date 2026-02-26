{{-- resources/views/crm/daily-credit-call/legal-modal.blade.php --}}
<form method="POST" action="{{ route('crm.daily-credit-calls.legalStore') }}" id="createForm">
    @csrf
    
       
    <div class="row mb-4">
        <div class="col-md-12 ">
            <div class="form-group">
                <label for="customer_name">Custmer Name</label>
                <input type="text" name="customer_name" class="form-control" id="customer_name" value="{{$customer->company_name}}" readonly>
                <input type="hidden" name="customer_id" value="{{$customer->id}}" >
                <input type="hidden" name="status" value="pending" > 
            </div>
        </div>

        <div class="col-md-6 ">
            <div class="form-group">
                <label for="task_type">Action Type</label> 
                <select name="task_type" id="task_type" class="form-control"> 
                    <option value="office_pad_notice">Office Pad Notice</option>
                    <option value="office_legal_notice">Office Legal Notice</option>
                    <option value="legal_notice">Legal Notice</option>
                    <option value="case">Case</option>
                    <option value="stop">Stop</option>
                </select>
            </div>
        </div>

        <div class="col-md-6 ">
            <div class="form-group">
                <label for="assign_to">Assign To</label> 
                <select name="assign_to" id="assign_to" class="form-control">
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div> 
        <div class="col-md-12 ">
            <div class="form-group">
                <label for="assign_remarks">Note</label>
                <textarea  name="assign_remarks" class="form-control" id="assign_remarks" required>Infrom me with the fastest action</textarea> 
            </div>
        </div>
    </div>
    <div class="modal-footer ">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-check"></i> Save
        </button>
    </div>
</form>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="col-md-12 " style='font-size:25px'>
            Previous Task List
        </div>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th style="width: 5%;">SL</th>
                    <th style="width: 15%;">Task Type</th>  
                    <th style="width: 15%;">Status</th> 
                    <th style="width: 25%;">Assign By</th>
                    <th style="width: 25%;">Assign Date</th>
                </tr>
            </thead>
            <tbody>  
                @if(!empty($legalTask) && count($legalTask) > 0)
                    @foreach($legalTask as $index => $entry)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{  ucwords(str_replace('_', ' ', $entry->task_type)) ?? '-' }}</td>   
                            <td>{{ $entry->status  ?? '-' }}</td>
                            <td>
                                {{  $entry->createdBy->name  ?? '-' }} 
                            </td> 
                            <td> 
                                {{ $entry->created_at ? substr($entry->created_at, 0, 10) : '-' }}
                            </td> 
                        </tr>  
                        <tr> 
                            <td colspan="5">{{ $entry->assign_remarks ?? '-' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">No records found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>