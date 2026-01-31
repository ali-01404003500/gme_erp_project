<!-- remark modal -->
<div class="modal fade" id="remarkModal" tabindex="-1" role="dialog" aria-labelledby="remarkModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="remarkModalLabel">Remark</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="remarkForm">
                    <div class="form-group">
                        <label for="remark" class="col-form-label">Remark:</label>
                        <input type="text" class="form-control" id="remark" name="remark" placeholder="Remark">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="remarkSubmitBtn">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#remarkSubmitBtn').click(function() {
            var remark = $('#remark').val();
            if (remark == '') {
                showToast('error', 'Please input remark');
                return;
            }
            $(this).attr('disabled', true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
            );
            $.ajax({
                url: "{{ route('instant-notification.remark') }}",
                type: 'POST',
                data: {
                    '_token': "{{ csrf_token() }}",
                    'id': "{{ $id }}",
                    'remark': remark
                },
                success: function(response) {
                    if (response.success) {
                        $('#remarkModal').modal('hide');
                        $('#remarkForm')[0].reset();
                        showToast('success', response.message);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    showToast('error', 'An error occurred. Please try again later.');
                },
                complete: function() {
                    $('#remarkSubmitBtn').attr('disabled',
                        false).html('Submit');
                }
            });
        });
    });
</script>
