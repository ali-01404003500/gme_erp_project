<div id="signature-display" class="signature-display">
        {{-- @dd($model->signature) --}}
    @if (isset($model) && isset($model->$field) && $model->$field)
        <img src="{{ $model->$field->signature }}" 
             alt="Receiver Signature" style="max-height: 100%;max-width:100%;">
        <div class="signature-timestamp"> Signed on: {{ $model->$field->updated_at }} </div>
    @else
        <div class="signature-placeholder">No signature captured</div>
    @endif
</div>
<div class="text-center mt-4"> 
    <button type="button" class="btn btn-sm btn-primary btn-signature no-print" 
            data-toggle="modal" data-target="#signatureModal"> 
        Capture Signature 
    </button> 
</div>    

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
                <div class="signature-pad-container w-full">
                    <canvas id="signature-pad" class="border w-full" style="height: 200px; width: 100%;"></canvas>
                    <div class="signature-timestamp" id="signature-timestamp"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="signature-controls btn-group">
                    <button id="clear-signature" class="btn btn-sm btn-danger">
                        <i class="las la-eraser"></i> Clear
                    </button>
                    <button id="save-signature" class="btn btn-sm btn-success">
                        <i class="las la-save"></i> Save
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
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
            $('#save-signature').click(function() {
                if (signaturePad.isEmpty()) {
                    alert('Please provide a signature first.');
                    return;
                }

                // Convert signature to image
                const signatureData = signaturePad.toDataURL();

                // Display loading state
                $('#save-signature').html('<i class="las la-spinner la-spin"></i> Saving...');
                $('#save-signature').prop('disabled', true);

                // Send to server via AJAX
                $.ajax({
                    url: "{{ route('keep_signature') }}",
                    method: 'POST',
                    data: {
                        signature: signatureData,
                        keep_signatureable_type: @json(get_class($model)),
                        keep_signatureable_id: '{{ $model->id }}',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Create timestamp for display
                            const timestamp = response.timestamp || new Date().toLocaleString();

                            // Update the display with the saved signature
                            $('#signature-display').html(`
                                <img src="${response.path}" alt="Receiver Signature" style="max-height: 120px; max-width:100%;">
                                <div class="signature-timestamp">Signed on: ${timestamp}</div>
                            `);

                            $('#signatureModal').modal('hide');
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
@endpush