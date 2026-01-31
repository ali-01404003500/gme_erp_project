@extends('sales::layouts.master') {{-- Assuming a master layout for the module --}}

@section('content')
<div class="container">
    <h1>Verification Details</h1>

    @if(isset($verificationData))
        <div class="card">
            <div class="card-header">
                Verification Request #{{ $verificationData->pending_ids ?? 'N/A' }}
            </div>
            <div class="card-body">
                <p><strong>Status:</strong> {{ $verificationData->status ?? 'N/A' }}</p>
                <p><strong>Created At:</strong> {{ $verificationData->created_at ?? 'N/A' }}</p>
                <p><strong>Remark:</strong> {{ $verificationData->remark ?? 'N/A' }}</p>

                {{-- Display other relevant details from $verificationData if available --}}
                {{-- For example: --}}
                {{-- <p><strong>User:</strong> {{ $verificationData->user->name ?? 'N/A' }}</p> --}}
                {{-- <p><strong>Request Type:</strong> {{ $verificationData->request_type ?? 'N/A' }}</p> --}}

                <hr>

                {{-- Form for actions --}}
                {{-- Replace 'your.verification.route' with the actual route name --}}
                {{-- Assuming the ID is available in $verificationData->id --}}
                <form action="{{ route('your.verification.route', $verificationData->id ?? 'placeholder_id') }}" method="POST">
                    @csrf

                    {{-- Add any hidden fields needed for the action (e.g., status) --}}
                    {{-- <input type="hidden" name="verification_id" value="{{ $verificationData->id ?? '' }}"> --}}

                    <button type="submit" name="action" value="accept" class="btn btn-success">
                        <i class="fas fa-check"></i> Accept
                    </button>

                    <button type="submit" name="action" value="deny" class="btn btn-danger ml-2">
                        <i class="fas fa-times"></i> Deny
                    </button>
                </form>
            </div>
        </div>
    @else
        <p>No verification data available.</p>
    @endif
</div>
@endsection

@push('scripts')
{{-- Add any necessary scripts here --}}
@endpush