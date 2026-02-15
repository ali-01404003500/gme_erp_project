@section('title', 'Collection')
@section('description', 'Record a new Collection')
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
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i> Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Collection</li>
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
                    <h4 class="text-capitalize breadcrumb-title">Collection</h4>
                    <x-error-alart />
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="form" action="{{ route('account.collections.collections.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="voucher_type" value="Collection">

                                <div class="row mt-1">
                                    <div class="col-sm-12 px-3">

                                        <!-- Collection Type, From, Date, Reference -->
                                        <div class="row mt-4 mb-4">
                                            <!-- Collection Type -->
                                            <div class="col-sm-4 my-1">
                                                <div class="input-group">
                                                    <span class="input-group-text">Collection Type</span>
                                                    <select name="collection_type" id="collectionType" class="form-control">
                                                        <option value="">-- Select Type --</option>
                                                        <option value="customer">Customer Account</option>
                                                        <option value="vendor">Vendor Account</option>
                                                        <option value="supplier">Supplier Account</option>
                                                        <option value="broker">Broker Account</option>
                                                        <option value="employee">Employee Account</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Collection From -->
                                            <div class="col-sm-4 my-1">
                                                <div class="input-group">
                                                    <span class="input-group-text">Collected From</span>
                                                    <select name="collection_from" id="collectionFrom" class="form-control tom-select"
                                                        data-placeholder="Select Account" disabled>
                                                        <option></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Collection Date -->
                                            {{-- <div class="col-sm-4 my-1">
                                                <div class="input-group">
                                                    <span class="input-group-text">Collection Date</span>
                                                    <input name="text" class="form-control flatdate text-center" type="text"
                                                        value="{{ old('date', date('Y-m-d')) }}" readonly>
                                                    @error('date')
                                                        <span class="text-danger"> {{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div> --}}
                                            
                                            <div class="col-sm-4 my-1 text-end text-danger">
                                                    <span>Balance : </span>
                                                    <span id="balance"></span>
                                            </div>
                                            
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mt-3 mb-3">
                                                <h5 class="text-uppercase">Collection Information</h5>
                                            </div>
                                            {{-- @dd($serviceMyTask?->payments) --}}
                                            <div class="col-md-12">
                                                @include("Services::service-my-task.paymets", ['payments' => null])
                                            </div>
                                        </div>


                                        <!-- Attachment -->
                                        <div class="row mt-2">
                                            <!-- Submit Buttons -->
                                            <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                                <div class="btn-group">

                                                    <input type="hidden" name="status" id="status" value="{{ $collection->status?? 'pending' }}">

                                                    <button type="submit" class="btn btn-sm btn-success save-btn" >
                                                        <i class="fa fa-save"></i> Submit
                                                    </button>
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
        $('#collectionType').on('change', function() {
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
                        });
                    }
                },
                error: function(xhr) {
                    toastr.error('Failed to load data. Please check the console for errors.');
                    console.error(xhr.responseText);
                }
            });
        });


       
        
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