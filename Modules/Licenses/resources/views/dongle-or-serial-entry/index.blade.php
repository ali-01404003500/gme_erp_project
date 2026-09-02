@section('title', 'Dongle/Serial List')
@section('description', 'Dongle/Serial List')
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
                                        {{ trans('menu.dongle-or-serial-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('licenses.dongle-or-serial-entries.create'))
                                    <a href="{{ route('licenses.dongle-or-serial-entries.create') }}" class="btn px-20 btn-primary ">
                                        <i class="las la-plus fs-16"></i>Add New
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.dongle-or-serial-list-menu-title') }}</h4>
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>
                                                <select name="customer_id" id="customer_id" class="form-control " data-placeholder="Select Customer">
                                                    <option value="">Select Customer</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="dongle_id" id="dongle_id" value="{{ request('dongle_id') }}" placeholder="Dongle Id">
                                            </td>
                                           
                                            <td colspan="5" class="text-right">
                                                <div class="btn-group btn-corner">
                                                    <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i>
                                                        Search</button>
                                                    <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i
                                                            class="fa fa-refresh"></i> Refresh</a>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table table-bordered dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $dongleOrSerialEntrys])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Customer Name</th>
                                        <th>Dongle</th>
                                        <th>Product Name</th> 
                                        <th>Type</th>
                                        <th>File Status</th>
                                        <th>Attachment</th>
                                        <th>Prepared By</th>
                                        <th>Status</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($dongleOrSerialEntrys as $value)
                                        <tr>
                                        <td class="text-center">{{ ($dongleOrSerialEntrys->currentPage() - 1) * $dongleOrSerialEntrys->perPage() + $loop->iteration  }}</td>
                                            <td>
                                                <a href="{{ route('licenses.dongle-or-serial-entries.show', $value->id) }}">
                                                    {{ $value->customer->company_name }}
                                                </a><br>
                                                <small class="text-muted"><i class="las la-map-marker me-1"></i>  {{ $value->customer->area?->area }}</small> 
                                            </td>
                                            <td>{{ $value->dongle_id }}</td>
                                            <td>
                                                {{ $value->product->withoutModelSuffix()->name }}<br>
                                                <small class="text-muted">Model: {{ $value->product->model }}</small> 
                                            </td> 
                                            <td>{{ $value->product_type }}</td>
                                            <td>
                                                @if($value->file_upload != null)
                                                    <span class="badge badge-round badge-success">Uploaded</span>
                                                @else
                                                    <span class="badge badge-round badge-danger">Not Uploaded</span>
                                                @endif
                                            </td>
                                            <td class="text-center"> 
                                                @php
                                                    $attachments = $value->file_upload ?? [];

                                                    if (is_string($attachments)) {
                                                        $attachments = json_decode($attachments, true) ?? [];
                                                    }

                                                    $attachments = is_array($attachments) ? $attachments : [];
                                                @endphp

                                                @foreach($attachments as $file)
                                                    @if(!empty($file))
                                                        <i class="fa fa-eye  view-attachment" data-url="{{ url($file) }}"></i> 
                                                    @endif
                                                @endforeach
                                                
                                            </td>

                                            <td>{{ $value->createdBy->name }}</td>
                                            <td>
                                                {{ $value->status }}
                                            </td>

                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if (hasPermission('licenses.dongle-or-serial-entries.update'))
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('licenses.dongle-or-serial-entries.edit', $value->id) }}"
                                                            title="Edit"><i class="far fa-edit"></i></a>
                                                    @endif
                                                  
                                                    @if (hasPermission('licenses.dongle-or-serial-entries.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('licenses.dongle-or-serial-entries.destroy', $value->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"
                                                            title="Delete"><i class="far fa-trash-alt"></i></button>
                                                    @endif
                                                    @if (hasPermission('licenses.dongle-or-serial-entries.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('licenses.dongle-or-serial-entries.show', $value->id) }}"
                                                            title="View"><i class="fas fa-eye"></i></a>
                                                        
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>


                            <div class="d-none">
                                <form class="delete-form" action="" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade inputForm-modal" id="recommendModal" tabindex="-1" role="dialog"
        aria-labelledby="recommendModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header" id="recommendModalLabel">
                    <h5 class="modal-title">Recommend </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="recommendForm">
                    @csrf
                    @method('put')
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Recomended Comments</label>
                            <div class="col-sm-12">
                                <textarea name="recommended_comments" id="recommended_comments" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Recommend</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade inputForm-modal" id="approveModal" tabindex="-1" role="dialog"
        aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header" id="approveModalLabel">
                    <h5 class="modal-title">Approve </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="approveForm">
                    @csrf
                    @method('put')
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Approve Comments</label>
                            <div class="col-sm-12">
                                <textarea name="approveed_comments" id="approveed_comments" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Attachment Modal -->
    <div class="modal fade" id="attachmentModal"   tabindex="-1"    aria-labelledby="attachmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attachmentModalLabel">
                        Attachment Preview
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe id="attachmentPreview" src=""  width="100%"  height="600px"  style="border: none;">
                    </iframe>
                </div>
            </div>
        </div>
    </div>


 
@endsection
@section('page_scripts')
 <script>
        $(document).ready(function() {
            const companySelect = new TomSelect("#customer_id", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('licenses.dongle-or-serial-autocomplete.customers') }}",
                        type: "GET",
                        data: { search: query },
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

            @if(!empty($customer))
                companySelect.addOption({
                    id: "{{ $customer->id }}",
                    text: "{{ $customer->name }}"
                });

                companySelect.setValue("{{ $customer->id }}");
            @endif
 
            
        });
        $(document).on('click', '.view-attachment', function () {
            var fileUrl = $(this).data('url');
            $('#attachmentPreview').attr('src', fileUrl);
            var attachmentModal = new bootstrap.Modal(
                document.getElementById('attachmentModal')
            );
            attachmentModal.show();
        });


        $('#attachmentModal').on('hidden.bs.modal', function () {
            $('#attachmentPreview').attr('src', '');
        });

    </script>

@endSection
