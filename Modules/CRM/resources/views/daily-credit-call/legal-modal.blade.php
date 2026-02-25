{{-- resources/views/crm/daily-credit-call/legal-modal.blade.php --}}
<form method="POST" action="{{ route('crm.daily-credit-calls.store') }}" id="createForm">
    @csrf
    
       
    <div class="row mb-4">
        <div class="col-md-12 ">
            <div class="form-group">
                <label for="customer_name">Custmer Name</label>
                <input type="text" name="customer_name" class="form-control" id="customer_name" value="{{$customer->company_name}}" readonly>
                <input type="hidden" name="customer_id" value="{{$customer->id}}" >
                <input type="hidden" name="status" value="pending" >
                <input type="hidden" name="assign_date" value="{{ date('Y-m-d') }}" >
            </div>
        </div>

        <div class="col-md-6 ">
            <div class="form-group">
                <label for="action_type">Action Type</label> 
            </div>
        </div>

        <div class="col-md-6 ">
            <div class="form-group">
                <label for="employee_name">Employee Name</label> 
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