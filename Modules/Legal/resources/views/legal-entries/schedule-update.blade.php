@section('title', 'Legal Schedule Update ')
@section('description', 'Legal Schedule Update ')
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
                                        {{ trans('Legal Schedule Update') }}</li>
                                </ol>
                            </nav>
                        </div>
                        {{-- <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">

                            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                <i class="las la-file-pdf fs-16"></i> PDF
                            </a>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Legal Schedule Update') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td style="width: 50%">
                                                    <select name="customer_id" id="customer_id"
                                                        class="form-control tom-select"
                                                        data-placeholder="Search by Customer">
                                                        <option value="all" selected>All</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->customer_id }}"
                                                                {{ request('customer_id') == $customer->customer_id ? 'selected' : '' }}>
                                                                {{ $customer->customer->company_name }}</option>
                                                        @endforeach
                                                    </select>

                                                </td>
                                                <td>
                                                    <select name="status" id="status" class="form-control tom-select"
                                                        data-placeholder="Search by status">
                                                        <option value="withdraw"
                                                            {{ request('status', 'withdraw') == 'withdraw' ? 'selected' : '' }}>
                                                            Withdraw</option>
                                                        <option value="running"
                                                            {{ request('status') == 'running' ? 'selected' : '' }}>Running
                                                        </option>
                                                    </select>

                                                </td>


                                                <td class="text-right" style="width: 30%">
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
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table id="zero-config" class="table dt-table-hover table-bordered" style="width:100%; table-layout: fixed;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 3%">Sl</th>
                                                    <th class="text-left" style="width: 35%">Case info</th>
                                                    <th class="text-left" style="width: 14%">Advocate Info</th>
                                                    <th class="text-left" style="width: 13%">Remarks</th>
                                                    <th class="text-left" style="width: 13%">Hajira Info</th>
                                                    <th class="text-left" style="width: 7%">Document</th>
                                                    <th class="text-center no-content" style="width: 15%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($legalEntrys as $key => $legal)
                                                    <tr>
                                                        <td class="text-center" style="vertical-align: top;">
                                                            {{ $key + 1 }}</td>
                                                        <td style="vertical-align: top; min-width: 200px;" class="text-wrap" >
                                                            <li>Case No : {{ $legal->case_no }}</li>
                                                            <li>Customer :
                                                                @foreach ($legal->convicts as $convict)
                                                                    {{ optional($convict->customer)->company_name }}
                                                                    @if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            </li>
                                                            <li>Convict : @foreach ($legal->convicts as $convict)
                                                                    {{ $convict->convict_name }}@if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            </li>
                                                            <li>Address : @foreach ($legal->convicts as $convict)
                                                                    {{ $convict->convict_address }}@if (!$loop->last)
                                                                        , <br>
                                                                    @endif
                                                                @endforeach
                                                            </li>

                                                        </td>
                                                        <td style="vertical-align: top;" class="text-wrap">
                                                            <li>
                                                                Name By : {{ $legal->advocate_name }} <br>
                                                            </li>
                                                            <li>
                                                                Phone : {{ $legal->advocate_phone }} <br>
                                                            </li>

                                                        </td>

                                                        <td style="vertical-align: top;" class="text-center">
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-info small-btn d-inline-block remarksBtn"
                                                                data-id="{{ $legal->id }}">
                                                                Details
                                                            </button>
                                                        </td>
                                                        <td style="vertical-align: top;">
                                                            @php
                                                                $lastHajira = $legal->hajiras->sortByDesc('hajira_date')->first();
                                                            @endphp

                                                            @if($lastHajira)
                                                                <p class="text-danger">Last Date: {{ $lastHajira->hajira_date }}</p>
                                                                
                                                                @foreach ($legal->hajiras->sortByDesc('hajira_date')->skip(1) as $hajira)
                                                                    <li>{{ $hajira->hajira_date }}</li>
                                                                @endforeach
                                                            @else
                                                                <p class="text-muted">No hajira date available</p>
                                                            @endif
                                                        </td>
                                                        <td style="vertical-align: top;">
                                                             @if($legal->attachment)
                                                            @foreach ($legal->attachment as $attachment)
                                                                @php
                                                                    $extension = pathinfo(
                                                                        $attachment,
                                                                        PATHINFO_EXTENSION,
                                                                    );
                                                                    $icon = 'nav-icon far fa-file'; // Default icon
                                                                    if (
                                                                        in_array($extension, [
                                                                            'jpg',
                                                                            'jpeg',
                                                                            'png',
                                                                            'gif',
                                                                        ])
                                                                    ) {
                                                                        $icon = 'nav-icon far fa-file-image';
                                                                    } elseif (in_array($extension, ['pdf'])) {
                                                                        $icon = 'nav-icon far fa-file-pdf';
                                                                    } elseif (in_array($extension, ['doc', 'docx'])) {
                                                                        $icon = 'nav-icon far fa-file-word';
                                                                    } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                                                        $icon = 'nav-icon far fa-file-excel';
                                                                    }
                                                                @endphp
                                                                <a href="{{ $attachment }}" target="_blank">
                                                                    <i class="{{ $icon }}"
                                                                        style="font-size: 20px;"></i>
                                                                </a>
                                                            @endforeach
                                                            @else
                                                            <p class="text-muted">N/A</p>
                                                            @endif
                                                        </td>

                                                        <td class="text-center">
                                                            @if ($legal->status == 'running')
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-sm btn-outline-primary small-btn d-inline-block scheduleBtn"
                                                                    data-id="{{ $legal->id }}"
                                                                    style="width: 80px;">Action</a>
                                                            @endif
                                                            <div class="btn-group">

                                                                @if (hasPermission('legal.legal-entries.approve') &&
                                                                        $legal->status == 'withdraw' &&
                                                                        $legal->approval_status == 'pending')
                                                                    <a href="{{ route('legal.legal-entries.approve', $legal->id) }}"
                                                                        class="btn btn-xs btn-outline-success approval-confirm-legal"
                                                                        data-action="{{ route('legal.legal-entries.approve', $legal->id) }}"
                                                                        data-confirm-title="Approve Withdraw?"
                                                                        data-confirm-message="Are you sure you want to approve this Withdraw?"
                                                                        data-confirm-icon="success" title="Approve Withdraw"
                                                                        data-confirm-text="Yes, Approve it!">
                                                                        <i class="fas fa-check"></i>
                                                                    </a>
                                                                @endif

                                                                @if (hasPermission('legal.legal-entries.deny') && $legal->status == 'withdraw' && $legal->approval_status == 'pending')
                                                                    <a href="{{ route('legal.legal-entries.deny', $legal->id) }}"
                                                                        class="btn btn-xs btn-outline-danger reject-confirm-legal"
                                                                        data-action="{{ route('legal.legal-entries.deny', $legal->id) }}"
                                                                        data-confirm-title="Reject Withdraw?"
                                                                        data-confirm-message="Are you sure you want to reject this Withdraw?"
                                                                        data-confirm-icon="warning"
                                                                        title="Reject Withdraw"
                                                                        data-confirm-text="Yes, Reject it!">
                                                                        <i class="fas fa-times"></i>
                                                                    </a>
                                                                @endif
                                                            </div>

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
    </div>
    <!-- Remarks Modal -->
    <div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="remarksModalLabel">Hajira Remarks Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <ul id="remarksList" class="list-group list-group-flush">
                        <!-- Dynamically loaded remarks will appear here -->
                    </ul>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Update Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="scheduleForm">
                    @csrf
                    <input type="hidden" name="legal_entry_id" id="legal_entry_id">

                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="scheduleModalLabel">Legal Schedule Update</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <strong>Case NO:</strong> <span id="modal_case_no" class="text-primary"></span><br>
                            <strong>Convict Name:</strong> <span id="modal_convict_names" class="text-primary"></span>
                        </div>
                        <div class="mb-3">
                            <label for="last_hajira_date">Last Hajira Date</label>
                            <input type="text" class="form-control" id="last_hajira_date" name="last_hajira_date"
                                readonly>
                        </div>

                        <div class="mb-3">
                            <label for="modal_status">Legal Status</label>
                            <select name="modal_status" id="modal_status" class="form-control">
                                <option value="running" {{ old('modal_status') == 'running' ? 'selected' : '' }}>Running
                                </option>
                                <option value="withdraw" {{ old('modal_status') == 'withdraw' ? 'selected' : '' }}>
                                    Withdraw</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="next_hajira_date" class="text-danger" id="hajiraLabel">
                                {{ old('modal_status') == 'withdraw' ? 'Withdraw Date *' : 'Next Hajira Date *' }}
                            </label>
                            <input type="text" class="form-control flatdate" id="next_hajira_date"
                                name="next_hajira_date" value="{{ old('next_hajira_date') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="file">File</label>
                            <x-file-uploader loadLater multiple name="attachment" id="attachment" />

                        </div>

                        <div class="mb-3">
                            <label for="hajira_remarks">Hajira Remarks</label>
                            <textarea class="form-control" name="hajira_remarks" id="hajira_remarks" rows="3"></textarea>
                        </div>

                        <div id="previousRemarks" class="mt-3"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"> Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('page_scripts')
    <script>
        $(document).ready(function() {
            function updateLabel() {
                const status = $('#modal_status').val();
                const label = (status === 'withdraw') ? 'Withdraw Date *' : 'Next Hajira Date *';
                $('#hajiraLabel').text(label);
            }

            // Initial check on page load
            updateLabel();

            // On status change
            $('#modal_status').on('change', updateLabel);
        });
    </script>

    <script>
        $(document).on('click', '.remarksBtn', function() {
            const legalId = $(this).data('id');

            $.get(`/legal/hajira-remarks/${legalId}`, function(data) {
                let listHtml = '';

                if (data.length === 0) {
                    listHtml = `<li class="list-group-item text-muted">No remarks available.</li>`;
                } else {
                    data.forEach(item => {
                            listHtml += `<li class="list-group-item text-wrap" style="word-break: break-word;">
                            <strong>Date:</strong> ${item.date} <br>
                            <strong>Note:</strong> <span class="d-block">${item.description ?? 'N/A'}</span>
                            </li>`;
                    });
                }

                $('#remarksList').html(listHtml);
                $('#remarksModal').modal('show');
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            // Open modal and load data
            $('.scheduleBtn').on('click', function() {
                let id = $(this).data('id');

                $.get("{{ url('legal/legal-schedule') }}/" + id, function(res) {
                    $('#legal_entry_id').val(res.id);
                    $('#last_hajira_date').val(res.last_hajira_date);
                    $('#modal_status').val(res.status).trigger('change'); // ✅ Set status
                    $('#next_hajira_date').val('');
                    $('#hajira_remarks').val('');
                    $('#modal_case_no').text(res.case_no);
                    $('#modal_convict_names').text(res.convict_name.join(', '));
                    // console.log(res.attachments);
                    const attachemets = document.getElementById('attachment');

                    if (attachemets.uploader) {
                        // console.log("uploader",attachemets.uploader);
                        attachemets.uploader.removeAllFiles();
                        // addExistingFile(res.attachments);
                        // console.log(res.attachments);

                        for (let i = 0; i < res.attachments.length; i++) {
                            if (res.attachments[i] == null) continue;
                            attachemets.uploader.addExistingFile(res.attachments[i]);
                        }

                    } else {
                        initializeFileUploader_attachment_attachment(undefined, true, res
                            .attachments);
                    }

                    let remarksHtml = '';
                    res.remarks.forEach(function(remark) {
                        remarksHtml += `<li class="text-wrap" style="word-break: break-word">Date: ${remark.date} :- ${remark.note}</li>`;
                    });
                    $('#previousRemarks').html(`<ul>${remarksHtml}</ul>`);

                    $('#scheduleModal').modal('show');
                });
            });

            // Submit form via AJAX
            $('#scheduleForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ url('/legal/legal-schedule/update') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        alert('Schedule updated!');
                        $('#scheduleModal').modal('hide');
                        location.reload(); // optional
                    },
                    error: function(xhr) {
                        alert('Error occurred!');
                    }
                });
            });
        });
    </script>
    <script>
        function approvalConfirm(e) {
            e.preventDefault();
            e.stopPropagation();

            const el = $(this);
            const url = el.data("action");
            const confirmTitle = el.data("confirm-title") || "Are you sure?";
            const confirmMessage = el.data("confirm-message") || "You won't be able to revert this!";
            const confirmIcon = el.data("confirm-icon") || "success";
            const confirmText = el.data("confirm-text") || "Yes, Approve it!";

            Swal.fire({
                title: confirmTitle,
                text: confirmMessage,
                icon: confirmIcon,
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: confirmText
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        function rejectConfirm(e) {
            e.preventDefault();
            e.stopPropagation();

            const el = $(this);
            const url = el.data("action");
            const confirmTitle = el.data("confirm-title") || "Are you sure?";
            const confirmMessage = el.data("confirm-message") || "You won't be able to revert this!";
            const confirmIcon = el.data("confirm-icon") || "warning";
            const confirmText = el.data("confirm-text") || "Yes, Reject it!";

            Swal.fire({
                title: confirmTitle,
                text: confirmMessage,
                icon: confirmIcon,
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: confirmText
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        $(document).ready(function() {
            $(".approval-confirm-legal").on("click", approvalConfirm);
            $(".reject-confirm-legal").on("click", rejectConfirm);
        });
    </script>

@endsection
