@section('title', 'Advance Cheque Receipt Detail')
@section('description', 'Advance Cheque Receipt Detail')
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
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i> Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Advance Cheque Receipt Detail
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .invoice-container {
                    width: 80%;
                    margin: 20px auto;
                    padding: 80px;
                    background-color: #fff;
                    border: 1px solid #ccc;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                .header {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                    margin-bottom: 20px;
                }

                .header img {
                    max-width: 100px;
                    margin-right: 20px;
                }

                .header h1 {
                    margin: 0;
                    font-size: 40px;
                    font-weight: bold;
                    color: rgb(0, 0, 187);
                }

                .header p {
                    margin: 5px 0;
                    font-size: 20px;
                }

                .title {
                    text-align: center;
                    margin-bottom: 20px;
                }

                .title h2 {
                    margin: 0;
                    font-size: 20px;
                    text-decoration: underline;
                }

                .requisition-info {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }

                .requisition-info table {
                    width: 100%;
                    border-collapse: collapse;
                }

                .requisition-info th,
                .requisition-info td {
                    padding: 5px;
                    text-align: left;
                    font-size: 14px;
                }

                .invoice-details {
                    margin-bottom: 20px;
                }

                .invoice-details table {
                    width: 100%;
                    border-collapse: collapse;
                }

                .invoice-details table,
                .invoice-details th,
                .invoice-details td {
                    border: 1px solid #000;
                }

                .invoice-details th,
                .invoice-details td {
                    padding: 8px;
                    font-size: 14px;
                    text-align: left;
                }

                footer {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 40px;
                }

                /* Signature Styles */
                .signature-display {
                    height: 80px;
                    border: 1px solid #ddd;
                    margin-top: 10px;
                    background-color: white;
                }

                .signature-placeholder {
                    color: #999;
                    font-style: italic;
                }

                /* Modal Styles */
                .signature-modal .modal-dialog {
                    max-width: 600px;
                }

                .signature-pad-container {
                    margin: 20px 0;
                }

                .signature-pad {
                    border: 1px solid #000;
                    background-color: #fff;
                    width: 100%;
                    height: 200px;
                }

                .signature-controls {
                    margin-top: 10px;
                    text-align: center;
                }

                .signature-timestamp {
                    font-size: 12px;
                    color: #666;
                    text-align: center;
                    margin-top: 5px;
                }
            </style>

            <div class="row">
                <div class="d-flex justify-content-between align-items-center user-member__title mb-30">
                    <h3 class="text-capitalize">Advance Cheque Receipt Detail</h3>
                    <div class="row">
                        <a href="{{ route('account.advance-cheque-entries.index') }}"
                            class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"
                            style="margin-right: 5px;">
                            <i class="fa fa-list"></i> List
                        </a>
                        <a href="{{ route('account.advance-cheque-entries.show', $advanceChequeEntry->id) }}?export=pdf"
                            target="_blank" class="btn btn-primary ml-auto btn-sm">PDF</a>

                    </div>
                </div>

                <div class="col-md-12 print-body">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="header">
                                <img src="{{ $company_info->company_logo }}" alt="Company Logo">
                                <div>
                                    <h1>{{ $company_info->company_name }}</h1>
                                    <p>{{ $company_info->company_bio }}</p>
                                    <p>{{ $company_info->company_address }}</p>
                                    <p>Hotline : {{ $company_info->company_phone }}</p>
                                    <p>e-mail : {{ $company_info->company_email }} web: {{ $company_info->website }}</p>
                                    <h2 class="title">Advance Cheque Money Receipt</h2>
                                </div>
                            </div>
                            <section class="requisition-info">
                                <div class="left">
                                    <table>
                                        <tr>
                                            <th>Receipt No</th>
                                            <td>:</td>
                                            <td>{{ $advanceChequeEntry->receipt_no }}</td>
                                        </tr>
                                        <tr>
                                            <th>Customer Name</th>
                                            <td>:</td>
                                            <td>{{ $advanceChequeEntry->customer->company_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td>:</td>
                                            <td>{{ $advanceChequeEntry->customer->address }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="right">
                                    <table>
                                        <tr>
                                            <th>Date</th>
                                            <td>:</td>
                                            <td>{{ $advanceChequeEntry->collection_date }}</td>
                                        </tr>
                                        <tr>
                                            <th>Prepared By</th>
                                            <td>:</td>
                                            <td>{{ $advanceChequeEntry->createdBy->name }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </section>

                            <section class="invoice-details">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Payment Mode</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($advanceChequeEntry->details as $key => $detail)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>Cheque ({{ $detail->bank->name }} -{{ $detail->branch->name }}-
                                                    {{ $detail->cheque_no }} -
                                                    {{ $detail->cheque_date }})
                                                </td>
                                                <td>{{ number_format($detail->amount) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <section class="requisition-info" style="display: flex; justify-content: space-between;">
                                    <div class="left" style="width: 70%;">
                                        <p>IN WORD : {{ convert_number($advanceChequeEntry->total_amount) }} Taka Only</p>
                                    </div>
                                    <div class="right" style="width: 30%;">
                                        <table style="border: none!important;">
                                            <tr>
                                                <td style="border: none!important;">Grand Total</td>
                                                <td style="border: none!important;">:</td>
                                                <td style="border: none!important; text-align: end;">
                                                    <strong>{{ number_format($advanceChequeEntry->total_amount) }}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </section>
                            </section>

                            <footer
                                style="display: flex; justify-content: space-between; margin-top: 40px; align-items: flex-end;">
                                <!-- Receiver Signature -->
                                <div style="text-align: center; width: 48%;">
                                    <div id="signature-display" class="signature-display">
                                        @if ($advanceChequeEntry->signature)
                                            <img src="{{ $advanceChequeEntry->signature }}"
                                                alt="Receiver Signature" style="max-height: 100%;">
                                            <div class="signature-timestamp"> Signed on:
                                                {{ $advanceChequeEntry->signature_timestamp }} </div>
                                        @else
                                            <div class="signature-placeholder">No signature captured</div>
                                        @endif
                                    </div>
                                    <div class="text-center mt-4"> <button type="button"
                                            class="btn btn-sm btn-primary btn-signature no-print" data-toggle="modal"
                                            data-target="#signatureModal"> Capture Signature </button> </div>
                                    <p>___________________________</p>
                                    <p style="margin-top:5px;">Receiver Signature</p>
                                </div>

                                <!-- Authorized Signature -->
                                <div style="text-align: center; width: 48%;">
                                    <p>___________________________</p>
                                    <p>Authorized Signature</p>
                                </div>
                            </footer>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   @push('modals')
        <!-- Signature Modal -->
    <div class="modal fade signature-modal" id="signatureModal" tabindex="-1" role="dialog"
        aria-labelledby="signatureModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signatureModalLabel">Digital Signature Pad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="signature-pad-container">
                        <canvas id="signature-pad" class="signature-pad"></canvas>
                        <div class="signature-controls btn-group">
                            <button id="clear-signature" class="btn btn-sm btn-danger">
                                <i class="las la-eraser"></i> Clear
                            </button>
                            <button id="save-signature" class="btn btn-sm btn-success">
                                <i class="las la-save"></i> Save
                            </button>
                        </div>
                        <div class="signature-timestamp" id="signature-timestamp"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
   @endpush

    <!-- Include Signature Pad library -->

@endsection
@section('page_scripts')
    <!-- Make sure these are included in the right order -->
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Signature Pad
            const canvas = document.getElementById('signature-pad');
            const signaturePad = new SignaturePad(canvas);

            // Adjust canvas size
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                signaturePad.clear();
            }

            // Clear signature
            $('#clear-signature').click(function() {
                signaturePad.clear();
                $('#signature-timestamp').text('');
            });

            // Save signature
            // Save signature
            $('#save-signature').click(function() {
                if (signaturePad.isEmpty()) {
                    alert('Please provide a signature first.');
                    return;
                }

                // Get current timestamp
                const now = new Date();
                const timestamp = now.toLocaleString();
                $('#signature-timestamp').text('Signed on: ' + timestamp);

                // Convert signature to image
                const signatureData = signaturePad.toDataURL();

                // Display loading state
                $('#save-signature').html('<i class="las la-spinner la-spin"></i> Saving...');
                $('#save-signature').prop('disabled', true);

                // Send to server via AJAX
                $.ajax({
                    url: "{{ route('account.advance-cheque-entries.save-signature', $advanceChequeEntry->id) }}",
                    method: 'POST',
                    data: {
                        signature: signatureData,
                        timestamp: timestamp,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the display with the saved signature
                            $('#signature-display').html(`
                    <img src="${response.path}" alt="Receiver Signature" style="max-height: 100%;">
                    <div class="signature-timestamp">Signed on: ${timestamp}</div>
                `);

                            // Close the modal after a short delay
                            setTimeout(function() {
                                $('#signatureModal').modal('hide');
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        alert('Error saving signature. Please try again.');
                        console.error(xhr.responseText);
                    },
                    complete: function() {
                        $('#save-signature').html('<i class="las la-save"></i> Save');
                        $('#save-signature').prop('disabled', false);
                    }
                });


                // Close the modal after a short delay
                setTimeout(function() {
                    $('#signatureModal').modal('hide');
                }, 1000);

                // AJAX code to save to server would go here
            });

            // Handle modal show event to resize canvas
            $('#signatureModal').on('shown.bs.modal', function() {
                resizeCanvas();
            });

            // Handle window resize
            $(window).resize(function() {
                if ($('#signatureModal').is(':visible')) {
                    resizeCanvas();
                }
            });
        });
    </script>
@endsection
