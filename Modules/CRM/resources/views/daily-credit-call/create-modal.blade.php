{{-- resources/views/crm/daily-credit-call/create-modal.blade.php --}}
<form method="POST" action="{{ route('crm.daily-credit-calls.store') }}" id="createForm">
    @csrf
    
       
    <div class="row mb-4">
        <div class="col-md-12 ">
            <div class="form-group">
                <label for="customer_name">Custmer Name</label>
                <input type="text" name="customer_name" class="form-control" id="customer_name" value="{{$customer->company_name}}" readonly>
                <input type="hidden" name="customer_id" value="{{$customer->id}}" >
                <input type="hidden" name="status" value="pending" >
                <input type="hidden" name="call_date" value="{{ date('Y-m-d') }}" >
            </div>
        </div>

        <div class="col-md-6 ">
            <div class="form-group">
                <label for="in_that_balance">Current Balance</label>
                <input type="text" name="in_that_balance" class="form-control" id="in_that_balance" value="{{$customer->getAccount()->balance}}" readonly>
            </div>
        </div>

        <div class="col-md-6 ">
            <div class="form-group">
                <label for="commitment_amount">Commitment Amount</label>
                <input type="number" name="commitment_amount" class="form-control" id="commitment_amount" value="0" required>
            </div>
        </div>
        <div class="col-md-6 ">
            <div class="form-group">
                <label for="commitment_date">Commitment Date</label>
                <input type="text" name="commitment_date" class="form-control flatdate" id="commitment_date"  value="" readonly required>
            </div>
        </div>    
        <div class="col-md-6 ">
            <div class="form-group">
                <label for="before_reminder_date">Remind Day before Commitment Date</label>
                <input type="text" name="before_reminder_date" class="form-control" id="before_reminder_date"  value="1" required>
            </div>
        </div>
        <div class="col-md-12 ">
            <div class="form-group">
                <label for="remarks">Note</label>
                <textarea  name="remarks" class="form-control" id="remarks" required></textarea> 
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