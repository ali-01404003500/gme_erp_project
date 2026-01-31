@section('title', 'Verification Requests')
@section('description', 'Verification Requests')
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
                                        {{ trans('menu.verification-requests') }}</li>
                                </ol>
                            </nav>
                        </div>
                        {{-- <div class="action-btn mt-sm-0 mt-15 row">
                            <a href="{{ route('sales.sales-orders.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                            <a href="{{ route('sales.sales-orders.create') }}" class="btn px-20 btn-primary btn-sm" style="margin-left: 5px;">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.verification-requests') }}</h4>
                    <x-error-alart />
                </div>

                <!-- Image Preview Modal -->
                <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imagePreviewModalLabel">Image Preview</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img id="modalImagePreview" src="" class="img-fluid" alt="Preview">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button id="modalImageLink" onclick="openImageNewTab(this.getAttribute('data-link'))" class="btn btn-primary">
                                    <i class="las la-external-link-alt"></i> Open in New Tab
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    @foreach ($verificationRequests as $verificationRequest)
                        @if ($verificationRequest)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card mb-4" data-request-id="{{ $verificationRequest->id }}">
                                        <div class="card-body">
                                            <div class="row" id="">
                                                <div class="col-md-4">
                                                    {{-- @dd($verificationRequest) --}}
                                                    <h6>ID #{{ $verificationRequest->id }}</h6>
                                                    <ul>
                                                        <li><i class="las la-user"></i> Created By: {{ $verificationRequest->created_by->name }}</li>
                                                        <li><i class="las la-clock"></i> Created At: {{ $verificationRequest->created_at }}</li>
                                                        {{-- <li><i class="las la-user"></i> Customer: {{ $verificationRequest->customer_name }}</li> --}}

                                                        @if ($verificationRequest->additional_data)
                                                            @php
                                                                $data = json_decode($verificationRequest->additional_data);
                                                                // @dd($data )
                                                            @endphp
                                                        
                                                            @if ($verificationRequest->additional_data)
                                                            
                                                                @php
                                                                    $data = json_decode($verificationRequest->additional_data);
                                                                    // @dd($data )
                                                                @endphp
                                                                @if (is_object($data) && property_exists($data, 'customer_name'))
                                                                     <li><i class="las la-user"></i> Customer:
                                                                         {{ $data->customer_name }}
                                                                     </li>
                                                                @endif
                                                                @if (is_object($data) && property_exists($data, 'image'))
                                                                    <li>
                                                                    @foreach ($data->image as $value)
                                                                        <div class="col-md-4">
                                                                            <p>Images:
                                                                                <a href="#" class="image-preview" data-image="{{$value}}">
                                                                                    <i class="las la-file-image"></i>
                                                                                </a>
                                                                            </p>
                                                                        </div>
                                                                    @endforeach
                                                                    </li>
                                                                @endif 
                                                            @endif


                                                                
                                                        @endif
                                                        <li>Remark: {{ $verificationRequest->remark }}</li>

                                    
                                                    </ul>
                                                    {{-- Assuming 'data' might still be relevant for some types, keep it commented out or adapt as needed --}}
                                                    {{-- <p>Invoice Image : <a href="{{ $verificationRequest->data }}" target="_blank"><i class="las la-file-image"></i></a></p> --}}

                                                    
                                                </div>
                                                <div class="col-md-8">
                                                    @if(!empty($verificationRequest->pending))
                                                            {{-- Loop through each pending change and display its details --}}
                                                            @foreach ($verificationRequest->pending as $pendingChange)
                                                                @if ($pendingChange->status == 'pending')
                                                                     <div class="card card-body mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" value="{{ $pendingChange->id }}" id="pending-{{ $pendingChange->id }}" checked>
                                                                            <div>
                                                                                <strong>Title:</strong> {{ $pendingChange->title }}<br>
                                                                                <strong>Requested Value:</strong> {{ $pendingChange->request_value }}<br>
                                                                                <strong>Status:</strong> {{ $pendingChange->status }}<br>

                                                                                {{-- Display details_data if it exists and is not null --}}
                                                                                
                                                                                @if($pendingChange->title  == 'Credit Limit Exceeded' )
                                                                                    
                                                                                    {{-- @dd($data ) --}}
                                                                                    @if(!empty($data->credit))
                                                                                        <div>Credit: {{ $data->credit }}</div>
                                                                                    @endif
                                                                                    @if(!empty($data->payment_mode))
                                                                                        <div>Payment Mode: {{ $data->payment_mode }}</div>
                                                                                    @endif
                                                                                    @if(!empty($data->payment_date))
                                                                                        <div>Payment Date: {{ $data->payment_date }}</div>
                                                                                    @endif
                                                                                    @if(!empty($pendingChange->details_data))
                                                                                        @if(isset($pendingChange->details_data) && !empty($pendingChange->details_data))
                                                                                            @php
                                                                                                $details_data = (object)$pendingChange->details_data;
                                                                                                // dd ($details_data);
                                                                                            @endphp
                                                                                            <div>Customer Name: {{ $details_data?->customer_info['customer_name']}}</div>
                                                                                            {{-- <div>Customer ID: {{ $details_data->customer_info['customer_id'] }}</div> --}}
                                                                                            {{-- <div>Customer Credit Limit: {{ $details_data->customer_info['credit_limit'] }}</div> --}}
                                                                                            @if(isset($data->credit) && !empty($data->credit))
                                                                                                <div>Customer Credit Limit:: {{ $data->credit }}</div>
                                                                                            @endif
                                                                                            {{-- @if(isset($data->payment_mode) && !empty($data->payment_mode))
                                                                                                <div>Payment Mode: {{ $data->payment_mode }}</div>
                                                                                            @endif --}}
                                                                                            {{-- @if(isset($data->payment_date) && !empty($data->payment_date))
                                                                                                <div>Payment Date: {{ $data->payment_date }}</div>
                                                                                            @endif --}}
                                                                                            <div>Customer Current Balance: {{ $details_data?->customer_info['current_balance'] }}</div>
                                                                                            <div>Customer AD Limit: {{ $details_data?->customer_info['ad_limit']??'' }}</div>
                                                                                            <div>Customer Images:
                                                                                                @foreach ($details_data?->customer_info['images'] as $image)
                                                                                                    <a href="#" class="image-preview" data-image="{{$image}}">
                                                                                                        <i style="font-size: 2rem;" class="las la-file-image"></i>
                                                                                                    </a>
                                                                                                @endforeach
                                                                                            </div>
                                                                                        @endif
                                                                                    @endif
                                                                                @elseif (str_starts_with($pendingChange->title,  'Discount Changed') )
                                                                                   @php
                                                                                            $details_data = (object)$pendingChange->details_data;
                                                                                            // dd ($details_data);
                                                                                        @endphp
                                                                                    @if( $details_data?->min??false)
                                                                                        <div>Discount: Range: {{ $details_data?->min }}  - {{ $details_data?->max }}</div>
                                                                                    @endif
                                                                                    @if(  $details_data?->price??false)
                                                                                        <div>Price: {{  $details_data?->price }}</div>
                                                                                    @endif
                                                                                    <div>Discount : {{ $pendingChange->request_value }}</div>
                                                                                

                                                                                @elseif (str_starts_with($pendingChange->title, ' Discount Range Exceeded for'))
                                                                                  {{-- @dd( $pendingChange->details_data,$details_data); --}}
                                                                                  @php
                                                                                            $details_data = (object)$pendingChange->details_data;
                                                                                            // dd ($details_data);
                                                                                        @endphp
                                                                                    @if(  $details_data?->product_id??false)
                                                                                        <div>Product Name: {{  $details_data?->product_id }}</div>
                                                                                    @endif
                                                                                    @if(  $details_data?->price??false)
                                                                                        <div>Price: {{  $details_data?->price }}</div>
                                                                                    @endif
                                                                                    @if(  $details_data?->min_discount??false)
                                                                                        <div>Min Discount: {{  $details_data?->min_discount }}</div>
                                                                                    @endif
                                                                                    @if(  $details_data?->max_discount??false)
                                                                                        <div>Max Discount: {{  $details_data?->max_discount }}</div>
                                                                                    @endif
                                                                                    <div>Discount : {{ $pendingChange->request_value }}</div>

                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                               
                                                            @endforeach
                                                    @else
                                                        <p>No pending changes for this request.</p>
                                                    @endif

                                                </div>
                                                @if( $verificationRequest->status == 'pending')
                                                    <div class="col-md-4 mt-4">
                                                        <div class="mt-3 d-flex mt-4 gap-4 text-center">
                                                            <button class="btn btn-success btn-sm px-4 rounded-pill shadow-sm">
                                                                <i class="las la-check me-1"></i> Accept
                                                            </button>
                                                            <button class="btn btn-danger btn-sm px-4 rounded-pill shadow-sm">
                                                                <i class="las la-times me-1"></i> Deny
                                                            </button>
                                                        </div>
                                                    </div>
                                                @else
                                                
                                                <div class="col-md-4 mt-4">
                                                    <div class="mt-3 d-flex mt-4 gap-4 text-center">
                                                        <button class="btn btn-success btn-sm px-4 rounded-pill shadow-sm" disabled>
                                                            <i class="las la-check me-1"></i> Responded
                                                        </button>
                                                    </div>
                                                </div>


                                                @endif

                                                <div class="col-md-8 mt-2">
                                                    <h6>Remarks for this request</h6>
                                                    <textarea class="form-control" rows="3" placeholder="Remarks Max (250 Characters)"></textarea>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection


@section('page_scripts')
    <script>

        function openImageNewTab(imageUrl) {
            // Check if the image is a base64 string
            if (imageUrl.startsWith('data:image')) {
                // For base64 images, open in new tab directly
                const newWindow = window.open();
                newWindow.document.write('<img src="' + imageUrl + '" style="max-width:100%; max-height:100%;">');
                newWindow.document.title = 'Base64 Image Preview';
            } else {
                // For normal images, open in new tab using the href attribute
                const newTab = window.open(imageUrl, '_blank');
                newTab.document.title = 'Image Preview';
            }
        }
        // Image preview modal handler
        $(document).on('click', '.image-preview', function(e) {
            e.preventDefault();
            const imageUrl = $(this).data('image');
            $('#modalImagePreview').attr('src', imageUrl);
            $('#modalImageLink').attr('data-link', imageUrl);
            $('#imagePreviewModal').modal('show');
        });

        // Existing JavaScript
        $(document).on('click', '.btn-success, .btn-danger', function () {
            let $btn = $(this);
            let $card = $btn.closest('.card');
            let verificationRequestId = $card.data('request-id');
            let action = $btn.hasClass('btn-success') ? 'accept' : 'deny';
            let remarks = $card.find('textarea').val();

            // Collect checked pending change IDs
            let pendingIds = [];
            $card.find('input[type="checkbox"]:checked').each(function () {
                pendingIds.push($(this).val());
            });

            if (pendingIds.length === 0) {
                toastr.error('Please select at least one pending change.');
                return;
            }

            $.ajax({
                url: '{{ route('verification.verify-otp') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    pending_ids: pendingIds,
                    remarks: remarks,
                    verification_request_id: verificationRequestId,
                    action: action
                },
                success: function (response) {
                    // Optionally, update UI or reload
                    location.reload();
                },
                error: function (xhr) {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        });
    </script>
@endsection