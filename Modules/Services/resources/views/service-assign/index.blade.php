@section('title', 'Service Assign List')
@section('description', 'Service Assign List')
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('service assign list') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">

                            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                <i class="las la-file-pdf fs-16"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('service assign list') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                     <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered w-25">
                                            <tr>
                                                <td >
                                                    <select name="status" id="status" class="form-control tom-select" data-placeholder="Search by status">
                                                        <option value="regular" selected>Regular</option>
                                                        <option value="junk" {{ request('status') == 'junk' ? 'selected' : '' }}>Junk</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-sm-12">
                                    <table id="zero-config" class="table dt-table-hover table-bordered" data-page='@include('utils.table_paginate', ['data' => $serviceTokens])' style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-right" style="width: 2%">Sl</th>
                                                <th class="text-left" style="width: 18%">Customer</th>
                                                <th class="text-left" style="width: 20%">Product info</th>
                                                <th class="text-left" style="width: 15%">Service Type</th>
                                                <th class="text-left" style="width: 10%">Problem Type</th>
                                                <th class="text-left" style="width: 10%">Date</th>
                                                <th class="text-left no-content" style="width: 15%">Emergency Note
                                                </th>
                                                <th class="text-center no-content" style="width: 10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($serviceTokens as $key => $token)
                                                <tr data-action="{{ $token->action }}"
                                                    style="background-color: {{ $token->action == 'Junk' ? 'rgb(168, 168, 168)' : ($token->action == 'Failed' ? 'rgba(255, 199, 206, 0.5)' : '') }};">
                                                    <td class="text-center" style="vertical-align: top;">
                                                        {{ $key + 1 }}</td>
                                                        <td style="vertical-align: top;" class="text-wrap">
                                                        {{ optional($token->customer)->company_name }} <br>
                                                        <span class="text-muted">{{ optional($token->customer)->address }}</span> 
                                                    
                                                    </td>
                                                    <td style="vertical-align: top;" class="text-wrap">
                                                        Invoice ID: {{ @$token->service->service_unique_id }} <br>
                                                        Product Name: {{ @$token->product->getRawOriginal('name') }} <br>
                                                          <span class="text-muted">Brand: {{ ((optional($token)->product)->brand)->getRawOriginal('name') ?? 'N/A' }}</span> <br>
                                                       <span class="text-muted">Serial No: {{ @$token->serial_number }}</span> 
                                                    </td>
                                                    
                                                    <td style="vertical-align: top;">{{ $token->service_type }}</td>
                                                    <td class="text-wrap" style="vertical-align: top; white-space: normal;">{{ $token->problem_type }}</td>
                                                    <td style="vertical-align: top;" class="text-wrap">
                                                        Invoice Date: {{ $token->invoice_date }} <br>  <br> 
                                                        Expire Date: {{ $token->expire_date }}
                                                    </td>
                                                    <td style="vertical-align: top;" class="text-wrap">
                                                        @foreach ($token->emergencyNotes as $emergencyNote)
                                                            <li style="word-break: break-word; overflow-wrap: break-word;">
                                                                Call By: {{ $emergencyNote->createdBy->name }} <br>
                                                                Call Date:
                                                                <b>{{ \Carbon\Carbon::parse($emergencyNote->created_at)->format('d-M-Y h:i A') }}</b>
                                                                <br>
                                                                Note: {{ $emergencyNote->note }}
                                                            </li>
                                                            <br><br>
                                                        @endforeach
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <form id="service-form-{{ $key + 1 }}"
                                                            action="{{ route('services.services-action-update', $token->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="mb-2">
                                                                <textarea name="note" class="form-control" placeholder="Note" required></textarea>
                                                            </div>
                                                            <select name="action" class="form-control tom-select mb-2">
                                                               
                                                                <option value="Pending"
                                                                    {{ $token->action == 'Pending' ? 'selected' : '' }}>
                                                                    Pending</option>
                                                                <option value="Failed"
                                                                    {{ $token->action == 'Failed' ? 'selected' : '' }}>
                                                                    Failed</option>
                                                                <option value="Junk"
                                                                    {{ $token->action == 'Junk' ? 'selected' : '' }}>
                                                                    Junk</option>
                                                            </select>
                                                            <button type="submit"
                                                                class="btn btn-sm btn-primary small-btn d-inline-block me-2"
                                                                style="width: 80px;">Update</button>
                                                            <a href="javascript:void(0);" 
                                                                class="btn btn-sm btn-outline-primary small-btn d-inline-block assignBtn" 
                                                                data-id="{{ $token->id }}" 
                                                                style="width: 80px;">Assign</a>

                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Assign Modal -->
<!-- Assign Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form id="assignForm">
        @csrf
        <input type="hidden" name="token_id" id="modal_token_id">

        <div class="modal-header">
          <h5 class="modal-title" id="assignModalLabel">Assign Engineer Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <h6><strong>Token Details</strong></h6>
          <div class="row">
            <div class="col-md-4">
              <label>Token No</label>
              <input type="text" class="form-control" id="modal_token_no" readonly>
            </div>
            <div class="col-md-4">
              <label>Token Date</label>
              <input type="text" class="form-control" id="modal_token_date" readonly>
            </div>
            <div class="col-md-4">
              <label>Status</label>
              <input type="text" class="form-control" id="modal_status" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col-md-8">
              <label>Product</label>
              <input type="text" class="form-control" id="modal_product" readonly>
            </div>
            <div class="col-md-4">
              <label>Serial Number</label>
              <input type="text" class="form-control" id="modal_serial_number" readonly>
            </div>
          </div>
          <div class="row ">
            <div class="col-md-8">
              <label>Customer Name</label>
              <input type="text" class="form-control" id="modal_customer_name" readonly>
            </div>
            <div class="col-md-4">
              <label>Customer Phone</label>
              <input type="text" class="form-control" id="modal_customer_phone" readonly>
            </div>

          </div>
          <div class="row mb-3">
            <div class="col-md-8">
              <label>Customer Address</label>
              <textarea class="form-control" id="modal_customer_address" readonly></textarea>
            </div>
            <div class="col-md-4">
              <label>Work Type</label>
              <input type="text" class="form-control" id="modal_work_type" readonly>
            </div>
          </div>

          <h6><strong>Problem</strong></h6>
          <div class="row mb-3">
            <div class="col-md-12">
              <label>Problem Type</label>
              <input type="text" class="form-control" id="modal_problem_type" readonly>
            </div>
            <div class="col-md-12 mt-2">
              <label>Problem Details</label>
              <textarea class="form-control" id="modal_problem_details" readonly></textarea>
            </div>
          </div>

          <h6><strong>Assign Section</strong></h6>
          <div class="row">
         
            <div class="col-md-6">
              <label>Engineer Name</label>
              <select name="engineer_id[]" id="engineer_id" class="form-control multi-select" required multiple>
                <option value="">Select Engineer</option>
                @foreach($engineers as $engineer)
                  <option value="{{ $engineer->id }}">{{ $engineer->full_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label>Service Date</label>
              <input type="text" class="form-control flatdate" name="service_date" required>
            </div>
            <div class="col-md-6 mt-3">
              <label>Service Priority</label>
              <select name="service_priority" class="form-control tom-select" required>
                <option value="HIGH">HIGH</option>
                <option value="MEDIUM">MEDIUM</option>
                <option value="LOW">LOW</option>
              </select>
            </div>
            <div class="col-md-6 mt-3">
              <label>Service Type</label>
              <select name="service_type" class="form-control tom-select" required>
                <option value="">Select</option>
                {{-- @foreach($serviceTypes as $service_type) --}}
{{-- <div role="listbox" tabindex="-1" class="ts-dropdown-content" id="tomselect-9-ts-dropdown">
    <div data-selectable="" data-value="3" class="option" role="option" id="tomselect-9-opt-1">
        ON CALL
    </div>
    <div data-selectable="" data-value="2" class="option" role="option" id="tomselect-9-opt-2">
        INHOUSE
    </div>
    <div data-selectable="" data-value="1" class="option selected active" role="option" id="tomselect-9-opt-3" aria-selected="true">
        ON SPOT
    </div>
</div> --}}
<option value="ON SPOT" selected>ON SPOT</option>
        <option value="ON CALL">ON CALL</option>
                <option value="IN HOUSE">IN HOUSE</option>


                    {{-- <option value="{{ $service_type->id }}"
                        {{ strcasecmp($service_type->name, 'on spot') === 0 ? 'selected' : '' }}>
                        {{ $service_type->name }}
                    </option> --}}
                {{-- @endforeach --}}
                {{-- <option value="ON SPOT">ON SPOT</option>
                <option value="PICK UP">PICK UP</option> --}}
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        <div id="docments" class="mt-3 d-flex gap-4 justify-content-center" >
          <!-- Documents will be loaded here -->
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('page_scripts')
<script>
    $(".multi-select").each(function () {
            new TomSelect(this, {
                    plugins: ['remove_button'],
            });
        });
    $(document).ready(function() {
        // Set the default status to 'regular' on page load
        $('#status').val('regular');

        // Function to filter the table based on selected status
        function filterTable() {
            var selectedStatus = $('#status').val();
            $('#zero-config tbody tr').each(function() {
                var action = $(this).find('td:nth-child(8) select').val(); // Get action from dropdown in the 8th column (Action column)
                if (selectedStatus === 'regular') {
                    // Show rows where action is Pending or Failed
                    if (action === 'Pending' || action === 'Failed') {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                } else if (selectedStatus === 'junk') {
                    // Show only rows where action is Junk
                    if (action === 'Junk') {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                }
            });
        }

        // Apply filter on dropdown change
        $('#status').change(function() {
            filterTable();
        });

        // Apply filter on page load (default 'regular')
        filterTable();
    });
</script>
<script>
$(function(){
    // 1) When you click the Assign button, fetch the token details and open modal
    $('.assignBtn').on('click', function() {
        let tokenId = $(this).data('id');

        $.ajax({
            url: '{{ route("services.get-token-details", ":id") }}'
                   .replace(':id', tokenId),
            method: 'GET',
            success: function(data) {
                // fill all your modal inputs from `data`
                $('#modal_token_id').val(data.id);
                $('#modal_token_no').val(data.token_no);
                $('#modal_token_date').val(data.token_date);
                $('#modal_status').val(data.status);
                $('#modal_problem_type').val(data.problem_type);
                $('#modal_problem_details').val(data.problem_details);
                $('#modal_product').val(data.product);
                $('#modal_serial_number').val(data.serial_number);
                $('#modal_customer_name').val(data.customer_name);
                $('#modal_customer_phone').val(data.customer_phone);
                $('#modal_customer_address').val(data.customer_address);
                $('#modal_work_type').val(data.work_type);
                // $('#docments').html(data.documents);
// console.log(data.documents);

               JSON.parse(data.documents)?.forEach(function(doc) {
                let extension = doc.split('.').pop().toLowerCase();
                let imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

                if (imageExtensions.includes(extension)) {
                    // Image preview with link
                    $('#docments').append(
                        `<div class="mb-2">
                            <a href="${doc}" target="_blank">
                                <img src="${doc}" alt="Image" style="max-width: 200px; max-height: 200px; border: 2px  solid #ccc; padding: 5px;">
                            </a>
                        </div>`
                    );
                } else {
                    // File preview with icon
                    $('#docments').append(
                        `<div class="mb-2">
                            <a href="${doc}" target="_blank">
                                <i class="fas fa-file-alt" style="margin-right:5px;"></i> ${doc.split('/').pop()}
                            </a>
                        </div>`
                    );
                }
            });



                $('#assignModal').modal('show');
            },
            error: function() {
                alert('Could not load token details.');
            }
        });
    });

    // 2) On modal form submit, POST to assign-engineer
    $('#assignForm').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: '{{ route("services.assign-engineer") }}',  // must match your route name
            method: 'POST',
            data: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                toastr.success(response.message); // or SweetAlert
                $('#assignModal').modal('hide');
                location.reload();             // or re-fetch just the table via AJAX
            },
            error: function(xhr) {
                let msg = xhr.responseJSON?.message || 'Assignment failed';
                alert(msg);
            }
        });
    });
});
</script>



@endsection
