<!-- Bootstrap Modal -->
<div class="modal fade" id="otpRequestModal" tabindex="-1" aria-labelledby="otpRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="otpRequestModalLabel">
                    <span class="text-danger">OTP Request</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Request Type</th>
                            <th>Details</th>
                            <th>Request Value</th>
                            <th><i class="fas fa-info-circle"></i> Status</th>
                        </tr>
                    </thead>
                    <tbody id="otpTableBody">
                        <!-- Data will be injected here -->
                    </tbody>
                </table>
                <textarea class="form-control" maxlength="250" placeholder="Remarks Max (250 Characters)"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" id="otpSubmitBtn">OTP</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            <div style="display: none;" id="otpProgressContent">
                <div
                    style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255, 255, 255, 0.815); display: flex; align-items: center; justify-content: center; z-index: 1050;">
                    <div class="dm-spin-dots spin-lg">
                        <span class="spin-dot badge-dot dot-primary"></span>
                        <span class="spin-dot badge-dot dot-primary"></span>
                        <span class="spin-dot badge-dot dot-primary"></span>
                        <span class="spin-dot badge-dot dot-primary"></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<input type="hidden" id="additional_data" value="">
<div id="form_otp_area">
    
</div>

<!-- Script to load table data -->
@push('script')
  <script src="https://cdn.jsdelivr.net/npm/workerpool@6.2.0/dist/workerpool.min.js"></script>

    <script>
            var pool = workerpool.pool(); // Create worker pool

        async function createOtpVerification(data) {
            try {
                const response = await $.ajax({
                    url: '{{ route('verification.create-otp') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        data: data
                    }
                });
                let exist = false;
                $('input[name="otp_verifications[]"]').each(function() {
                    const existingData = JSON.parse($(this).val());
                    if (existingData.title === response.title) {
                        exist = true;
                        $(this).val(JSON.stringify(response));
                    }
                });

                if (!exist) {
                    $('#form_otp_area').append(
                        $('<input >').attr({
                            type: 'hidden',
                            name: 'otp_verifications[]',
                            value: JSON.stringify(response)
                        })
                    );
                }
            } catch (xhr) {
                toastr.error('An error occurred. Please try again.');
            }
        }

        async function deleteOtpVerification(title) {
            const verificationToDelete = $('input[name="otp_verifications[]"]').filter(function() {
                const existingData = JSON.parse($(this).val());
                return existingData.title == title;
            });
            console.log(verificationToDelete);


            if (verificationToDelete.length) {
                try {
                    await $.ajax({
                        url: '{{ route('verification.delete-otp') }}',
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ...JSON.parse($(verificationToDelete[0]).val()),
                        },
                    });
                    console.log('OTP verification deleted successfully.');
                } catch (xhr) {
                    console.log('An error occurred. Please try again.');
                } finally{
                    verificationToDelete.remove();
                }
            }
        }


        async function updateOtpVerification(data) {
            const existingVerification = $('input[name="otp_verifications[]"]').filter(function() {
                const existingData = JSON.parse($(this).val());
                return existingData.title === data.title;
            });

            await createOtpVerification(JSON.stringify({
                    ...JSON.parse(existingVerification.val()||"{}"),
                    ...data,status: 'pending'
                }));
        }

        async function updateOtpVerifications() {
            const existingVerificationIds = $('input[name="otp_verifications[]"]').map(function(){
                const existingData = JSON.parse($(this).val());
                return existingData.id;
            }).get();

            try {
                const response = await $.ajax({
                    url: '{{ route('verification.update-otp') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        verification_ids: existingVerificationIds
                    }
                });
                $('input[name="otp_verifications[]"]').each(function(index) {
                    $(this).val(JSON.stringify(response[index]));
                    console.log("verifications Response: ", response[index]);
                });

                if (typeof checkExistingOtpVerifications === 'function') {
                    checkExistingOtpVerifications();
                }
            } catch (xhr) {
                toastr.error('An error occurred. Please try again.');
            }
        }

      

        function updateOtpAdditionalData(data) {
            $('#additional_data').val(JSON.stringify(data));
        }

        $(document).ready(function() {
            // Prevent form submission
            $('#status').closest('form').on('submit', async function(e) {
                console.log("status", $('#status').val());
                const from = this;
                $('button#approve').addClass('btn-loading');
                if ($('#status').val() != 'approved') {
                    from.submit();
                    return true; // Allow form submission if status is not approved
                }

                e.preventDefault();
                if(window.pendingCall && window.pendingCall.length > 0){

                //    window.pendingCall.shift();
                    await window.idleCallback(async () => {
                        // Assuming window.pendingCall is an array of functions
                        const executedNames = new Set();

                        // Process from end to beginning to get latest functions first
                        for (let i = window.pendingCall.length - 1; i >= 0; i--) {
                            const fn = window.pendingCall[i];
                            
                            // Check if function has a name property
                            if (fn.name && fn.name !== '') {
                                // If this is the first time seeing this function name from the end
                                if (!executedNames.has(fn.name)) {
                                    await fn();
                                    executedNames.add(fn.name);
                                }
                                // Remove this function from array since we processed it (or will ignore it)
                                window.pendingCall.splice(i, 1);
                            }
                        }

                        // Now process remaining functions without names
                        for (let i = 0; i < window.pendingCall.length; i++) {
                            await window.pendingCall[i]();
                        }
                        // window.pendingCall.length = 0; // Clear array
                        window.pendingCall.splice(0, window.pendingCall.length);
                    });
                  
                }
                if (typeof additionalSubmitPending === 'function') {
                    const submitPending = additionalSubmitPending();
                    if(!submitPending){
                        return false;
                    }
                }

                const pendingVerification = $('input[name="otp_verifications[]"]').filter(function() {
                    const existingData = JSON.parse($(this).val());
                    console.log("Existing Data:", existingData);
                    
                    return existingData.status == "pending" ;
                });
                const denayedVerification = $('input[name="otp_verifications[]"]').filter(function() {
                    const existingData = JSON.parse($(this).val());
                    return existingData.status == "denied";
                });

                if (pendingVerification.length == 0) {
                    if (denayedVerification.length > 0) {
                        const deniedTitles = denayedVerification.map(function() {
                            return JSON.parse($(this).val()).title;
                        }).get();
                        toastr.error('Denied verifications: ' + deniedTitles.join(', ') + '. Please resolve them before submitting.');
                        return false;
                    }
                    if(typeof additionalSubmit === 'function'){
                        additionalSubmit(from);
                    }else{
                        from.submit();
                    }

                    return true; // Allow form submission if no pending OTP verifications
                }
                $('#otpRequestModal #otpSubmitBtn').show();
                pendingVerification.each(function() {
                    const data = JSON.parse($(this).val());
                    console.log("Pending Verification:", data);
                });
                // Data load in modal
                $('#otpTableBody').empty();
                pendingVerification.each(function() {
                    const data = JSON.parse($(this).val());
                    console.log("Pending Verification:", data.details_data);
                    
                    const details = data.details_data ? ((data.details_data?.product_id?`
                        <div><strong>Product:</strong> ${data.details_data.product_id || ''}</div>
                        <div><strong>Quantity:</strong> ${data.details_data.quantity || ''}</div>
                        <div><strong>Price:</strong> ${data.details_data.price || ''}</div>
                    `:'')
                    +(
                        data.details_data?.min_discount?`
                            <div><strong>Min Discount:</strong> ${data.details_data.min_discount || ''}</div>
                            <div><strong>Max Discount:</strong> ${data.details_data.max_discount || ''}</div>
                        `:'')
                    
                    +(data.details_data?.credit_limit?`
                            <div class="form-group">
                                <label for="credit">Credit</label>
                                <input type="number" id="credit" class="form-control" value="${data.details_data.credit_limit}" placeholder="Credit Amount">
                            </div>

                            <div class="form-group">
                                <label for="payment_mode">Payment Mode</label>
                                <select id="payment_mode" class="form-control">
                                    <option value="">Select pay mode</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Online Deposit">Online Deposit</option>
                                    <option value="bKash">bKash</option>
                                    <option value="Nagad">Nagad</option>
                                    <option value="Rocket">Rocket</option>
                                    <option value="Card Payment">Card</option>
                                    <option value="EMI">Payment  EMI</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="payment_date">Payment Date</label>
                                <input type="text" id="payment_date" class="form-control">
                            </div>
                    `:"")) : '';
                    const row = `
                        <tr data-id="${data.id}">
                            <td>${data.title}</td>
                            <td>${details}</td>
                            <td>${data.request_value}</td>
                            <td>
                                ${
                                    data.status === 'pending' ?
                                     '<i class="fas fa-hourglass fa-2x text-warning" title="Pending"></i>' :
                                    data.status === 'approved' ?
                                     '<i class="fas fa-check-circle fa-2x text-success" title="Approved"></i>' :
                                    '<i class="fas fa-times-circle fa-2x text-danger" title="Rejected"></i>'
                                }
                            </td>
                        </tr>
                    `;



                    $('#otpTableBody').append(row);
                    $('#otpTableBody').find("#payment_date").each(function () {
                        $(this).flatpickr({
                            dateFormat: 'd-m-Y',
                            allowInput: true,
                            minDate: new Date().fp_incr(1)
                        });
                    })
                });
                $('button#approve').removeClass('btn-loading');

                $('#otpRequestModal').modal('show');

                toastr.error('Please complete the OTP verification before submitting.');
                $('#status').val('pending');

                console.log("prevented for opt validation");

                return true;
            });
        });

        // OTP Submission Handler
        $('#otpSubmitBtn').click(async function() {
            const remark = $('#otpRequestModal textarea').val().trim();
            const pendingIds = $('input[name="otp_verifications[]"]').filter(function() {
                return JSON.parse($(this).val()).status == 'pending';
            })
            .map(function() {
                return JSON.parse($(this).val()).id;
            })
            .get();
            if (pendingIds.length === 0) {
                toastr.error('No pending verifications found');
                return;
            }

            if (!remark) {
                toastr.error('Please enter a remark.');
                return;
            }
            
            $("#otpProgressContent").show();


            // Submit OTP request
            (
                async function() {
                    const additionalData = typeof getOtpAdditionalData === 'function' ? await getOtpAdditionalData() : null;
                    $.ajax({
                        url: '{{ route('notifications.opt-verification-request') }}',
                        method: 'POST',
                        data: {
                            ids: pendingIds,
                            remark: remark,
                            additional_data: additionalData? JSON.stringify(additionalData): null,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                startPolling(response.request_id);
                            } else {
                                showResult('error', 'Submission Failed', response.message ||
                                    'Failed to submit OTP request');
                            }
                        },
                        error: function(xhr) {
                            showResult('error', 'Error', xhr.responseJSON?.message || 'An error occurred');
                        }
                    });
                }
            )();
        });

        // Polling function
        function startPolling(requestId) {
            let pollCount = 0;
            const maxPolls = 1800; // 30 minute timeout (1800 seconds)

            // show progress modal

            const pollInterval = setTimeout(function poll() {
                pollCount++;

                $.ajax({
                    url: '{{ route('notifications.otp-verification-status') }}',
                    method: 'GET',
                    data: {
                        request_id: requestId
                    },
                    success: function(response) {
                        if (response.status === 'responded') {
                            clearTimeout(pollInterval);
                            let details = '';

                            // showResult('success', 'Approval Status', details);
                            $("#otpProgressContent").hide();
                            if (response.data.denied_ids && response.data.denied_ids.length > 0) {
                                response.data.denied_ids.forEach(id => {
                                    const row = $(`#otpTableBody tr[data-id="${id}"]`);
                                    row.find('td:nth-child(4)').html(
                                        `<i class="fas fa-times-circle fa-2x text-danger" title="Rejected"></i>`
                                        );
                                })
                            }
                            // Update otpTableBody with approved status
                            if (response.data.approved_ids && response.data.approved_ids.length > 0) {
                                response.data.approved_ids.forEach(id => {
                                    const row = $(`#otpTableBody tr[data-id="${id}"]`);
                                    row.find('td:nth-child(4)').html(
                                        `<i class="fas fa-check-circle fa-2x text-success" title="Approved"></i>`
                                    );
                                });
                            }


                            // remark append

                            if (response.data.responded_remarks) {
                                $('#otpRequestModal .modal-body').append(
                                    `<p class="mt-2"> <strong>Remark:</strong> ${response.data.responded_remarks}</p>`
                                );
                            }


                            $('#otpSubmitBtn').hide();
                            updateOtpVerifications();
                            // setTimeout(() => location.reload(), 2000);
                        } else if (pollCount >= maxPolls) {
                            clearTimeout(pollInterval);
                            showResult('warning', 'Timeout', 'Approval is taking longer than expected');
                            $("#otpProgressContent").hide();
                        } else {
                            setTimeout(poll, 1000); // Poll every second
                        }
                    },
                    error: function() {
                        clearTimeout(pollInterval);
                        showResult('error', 'Error', 'Failed to check approval status');
                    }
                });
            }, 1000);

        }

        // Show result modal
        function showResult(type, title, message) {
            $('#otpProgressModal').modal('hide');

            const resultModal = $('#otpResultModal');
            const icon = $('#resultIcon');
            const resultTitle = $('#resultTitle');
            const resultMessage = $('#resultMessage');

            icon.removeClass().addClass('fas display-4 mb-3');

            if (type === 'success') {
                icon.addClass('fa-check-circle text-success');
                resultMessage.html(message); // Use html() instead of text() to render HTML content
            } else if (type === 'error') {
                icon.addClass('fa-times-circle text-danger');
                resultMessage.text(message);
            } else {
                icon.addClass('fa-exclamation-circle text-warning');
            }

            resultTitle.text(title);
            resultMessage.text(message);
            resultModal.modal('show');
        }
    </script>
@endpush
