@if($success)
    <div class="otp-verification-success">
        <h3>OTP Verification Successful</h3>
        <div class="verification-data">
            <p><strong>Request ID:</strong> {{ $verificationData['request_id'] ?? 'N/A' }}</p>
            <p><strong>Status:</strong> Verified</p>
        </div>

        @if(!empty($pendingVerifications))
            <div class="pending-verifications">
                <h4>Pending Verifications:</h4>
                <ul>
                    @foreach($pendingVerifications as $verification)
                        <li>
                            ID: {{ $verification->id }} - 
                            Status: {{ $verification->status }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@else
    <div class="otp-verification-error alert alert-danger">
        <h3>Verification Failed</h3>
        <p>{{ $message }}</p>
    </div>
@endif