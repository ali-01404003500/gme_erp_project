@section('title', 'Edit Collection')
@section('description', 'Edit an existing Collection')
@extends('layout.app')
@section('page-head')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="las la-home"></i> Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('account.collections.collections.index') }}">Collection</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit Collection</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 row">
                            @if (hasPermission('account.collections.collections.index'))
                                <a href="{{ route('account.collections.collections.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                                    <i class="fa fa-list"></i> List
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">Edit Collection</h4>
                    <x-error-alart />
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="form" action="{{ route('account.collections.collections.update', $collection->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="voucher_type" value="Collection">

                                <div class="row mt-1">
                                    <div class="col-sm-12 px-3">
                                        {{-- @dd($collection) --}}
                                        <!-- Collection Type, From, Date, Reference -->
                                        <div class="row mt-4 mb-4">
                                            <!-- Collection Type -->
                                            <div class="col-sm-4 my-1">
                                                <div class="input-group">
                                                    <span class="input-group-text">Collection Type</span>
                                                    {{-- @dd($collection->collection_from_type) --}}
                                                    @php
                                                        $collection_type = match ($collection->collection_from_type) {
                                                            "Modules\CRM\Models\Customer\Customer" => "customer",
                                                            "Modules\Purchase\Models\Vendor" => "vendor",
                                                            "Modules\Purchase\Models\Supplier" => "supplier", 
                                                            "Modules\CRM\Models\Customer\Broker" =>  "broker",
                                                            "Modules\HRMS\Models\Employee" =>  "employee",
                                                            default => "",
                                                        };
                                                    @endphp
                                                    <select name="collection_type" id="collectionType" class="form-control tom-select">
                                                        <option value="">-- Select Type --</option>
                                                        <option value="customer" {{ $collection_type == 'customer' ? 'selected' : '' }}>Customer Account</option>
                                                        <option value="vendor" {{ $collection_type == 'vendor' ? 'selected' : '' }}>Vendor Account</option>
                                                        <option value="supplier" {{ $collection_type == 'supplier' ? 'selected' : '' }}>Supplier Account</option>
                                                        <option value="broker" {{ $collection_type == 'broker' ? 'selected' : '' }}>Broker Account</option>
                                                        <option value="employee" {{ $collection_type == 'employee' ? 'selected' : '' }}>Employee Account</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Collection From -->
                                            <div class="col-sm-4 my-1">
                                                <div class="input-group">
                                                    <span class="input-group-text">Collected From</span>
                                                    <select name="collection_from" id="collectionFrom" class="form-control" data-placeholder="Select Account">

                                                        <option value="{{ $collection->collectionFrom->id ?? '' }}" selected>
                                                            @if($collection->collection_from_type === "Modules\HRMS\Models\Employee")
                                                                {{ $collection->collectionFrom->full_name ?? 'N/A' }}
                                                            @elseif($collection->collection_from_type === "Modules\CRM\Models\Customer\Broker")
                                                                {{ $collection->collectionFrom->broker_name ?? 'N/A' }}
                                                            @else
                                                                {{ $collection->collectionFrom->company_name ?? 'N/A' }}
                                                            @endif
                                                        </option>


                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 my-1 text-end text-danger">
                                                    <span>Balance : </span>
                                                    <span id="balance"></span>
                                            </div>

                                        </div>
                                      
                                        {{-- @dd($collection->payments) --}}
                                        <div class="row">
                                            <div class="col-md-12 mt-3 mb-3">
                                                <h5 class="text-uppercase">Collection Information</h5>
                                            </div>
                                            <div class="col-md-12">
                                                @include("Services::service-my-task.paymets", ['payments' => $collection->payments])
                                            </div>
                                        </div>


                                        <!-- Attachment -->
                                        <div class="row mt-2">
                                            <!-- Submit Buttons -->
                                            <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                                <div class="btn-group">

                                                    <input type="hidden" name="status" id="status" value="{{ $collection->status?? 'pending' }}">

                                                    <button type="submit" class="btn btn-sm btn-success save-btn" >
                                                        <i class="fa fa-save"></i> Update
                                                    </button>
                                                     
                                                    @if(hasPermission('account.collections.collections.approve') &&  $collection->status == "pending")
                                                        <button type="submit" class="btn btn-sm btn-success save-btn" id="action_approve">
                                                            <i class="fa fa-check"></i> Update & Approve
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
<script>
     $(document).ready(function() {


        $('#action_verify').click(function() {
            $("#status").val("verified");
        });

        $('#action_approve').click(function() {
            $("#status").val("approved");
        });

        $('#action_deny').click(function() {
            $("#status").val("denied");
        });


        /*$('#collectionType').on('change', function() {
            var collectionType = $(this).val();
            var collectionFromSelect = $('#collectionFrom');
            var collectionFromTomSelect = collectionFromSelect[0].tomselect;

            // Clear current options and disable
            collectionFromTomSelect.clear();
            collectionFromTomSelect.clearOptions();
            collectionFromSelect.prop('disabled', true);
            collectionFromTomSelect.disable();

            if (!collectionType) {
                return;
            }

            // NOTE: You need to create a route and controller method to handle this AJAX request.
            // See the explanation below the code block.
            $.ajax({
                url: `{{ route('account.get-accounts-by-type') }}?type=${collectionType}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    collectionFromSelect.prop('disabled', false);
                    collectionFromTomSelect.enable();
                    if (data && data.length > 0) {
                        // The controller should return an array of objects with 'id' and 'name'
                        data.forEach(function(item) {
                            collectionFromTomSelect.addOption({
                                value: item.id,
                                text: item.name
                            });
                            if(item.id == "{{$collection->collection_from_id}}") {
                                collectionFromTomSelect.setValue(item.id);
                            }
                        });
                    }
                },
                error: function(xhr) {
                    toastr.error('Failed to load data. Please check the console for errors.');
                    console.error(xhr.responseText);
                }
            });
        });*/

         $('#collectionType').trigger('change');

        let collectionType = $('#collectionType').val();
        const companySelect = new TomSelect("#collectionFrom", {
            valueField: "id",
            labelField: "text",
            searchField: [], 
            load: function(query, callback) {

                if (!query.length || query.length < 2) return callback();

                $.ajax({
                    url: "{{ route('account.collections.collections-autocomplete.customers') }}",
                    type: "GET",
                    data: { search: query,type: collectionType },
                    success: function(res) {
                        companySelect.clearOptions();
                        callback(res.map(item => ({ id: item.id, text: item.label })));
                    },
                    error: function() {
                        callback();
                    }
                });
            }
        }); 

        $('#collectionType').on('change', function() {

            collectionType = $(this).val();

            companySelect.clear();
            companySelect.clearOptions();

        });


        @if(request('collectionFrom'))
            companySelect.addOption({
                id: "{{ request('collectionFrom') }}",
                text: "{{ request('collectionFrom') }}"
            });
            companySelect.setValue("{{ request('collectionFrom') }}");
        @endif
       
        
        $("#collectionFrom").on('change', function() {
            var accountId = $(this).val();
            var collectionType = $('#collectionType :selected').val();
            console.log({accountId, collectionType});
            
            if(!collectionType){
                toastr.error('Select Collection Type');
                return ;
            }
            if( !accountId) {
                toastr.error('Select Account');
                return;
            }
            $.ajax({
                url: `{{ route('account.get-ballance') }}?account_id=${accountId}&type=${collectionType}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        console.log(data);
                        let currentDate = new Date().toISOString().slice(0, 10); 
                        if(collectionType=="customer"){ 
                            const balanceLink = `{{ route('account.report.customer-ledger') }}?account_id=${data.id}&from=2021-10-05&to=${currentDate}`;
                            $('#balance').html('<a href="'+balanceLink+'" target="_blank">'+data.balance+'</a>')
                        }
                        else if(collectionType=="vendor"){ 
                            const balanceLink = `{{ route('account.report.vendor-ledger') }}?account_id=${data.id}&from=2021-10-05&to=${currentDate}`;
                            $('#balance').html('<a href="'+balanceLink+'" target="_blank">'+data.balance+'</a>')
                        }
                        else if(collectionType=="supplier"){ 
                            const balanceLink = `{{ route('account.report.supplier-ledger') }}?account_id=${data.id}&from=2021-10-05&to=${currentDate}`;
                            $('#balance').html('<a href="'+balanceLink+'" target="_blank">'+data.balance+'</a>')
                        }  
                        else{       
                            $('#balance').text(data.balance);
                        } 
                        updatePayable(Number(data.balance));
                        // Populate additional details based on the response
                        // $('#collectionType').val(data.type).trigger('change');
                    }
                },
                error: function(xhr) {
                    toastr.error('Failed to load details. Please check the console for errors.');
                    console.error(xhr.responseText);
                }
            });
        });



    });
</script>
@stack('script')
@endsection