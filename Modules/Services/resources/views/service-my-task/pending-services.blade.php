{{-- @dd($serviceMyTask->pendingServiceTokens) --}}

<table class="table table-bordered">
    <thead>
        <tr>
            <th width="5%">Added</th>
            <th>Product</th>
            <th>Serial</th>
            <th>Assigned</th>
            <th>Service Type</th>
        </tr>
    </thead>
    <tbody>
        {{-- current token --}}
        {{-- @dd($serviceMyTask->pendingServiceTokens->pluck('service_token_id')->toArray()); --}}
        <tr id="token_{{$serviceToken->id}}">
            <td>
                <div class="form-check" style="opacity: 0.5; pointer-events: none">
                    <input class="form-check-input" type="checkbox" value="{{$serviceToken->id}}" id="token_{{$serviceToken->id}}" name="pending_token_ids[]" checked>
                </div>
            </td>
            <td>{{ $serviceToken->product?->name }}</td>
            <td>{{$serviceToken->serial_number??'N/A' }}</td>
            <td>{{ $serviceToken->engineerAssign->engineers->pluck('full_name')->join(', ')}}</td>
            <td>{{ $serviceToken->service_type }}</td>
        </tr>
        <tr>
            <td colspan="5">
                <label for="description_{{ $serviceToken->id }}">Description</label>
                <textarea name="pending_descriptions[{{ $serviceToken->id }}]" id="description_{{ $serviceToken->id }}" class="form-control" rows="3">{{ old('pending_descriptions.' . $serviceToken->id, $serviceMyTask?->pendingServiceTokens?->firstWhere('service_token_id', $serviceToken->id)?->description ?? '') }}</textarea>
            </td>
        </tr>

        {{-- pending tokens --}}
        @foreach ($pendingServiceTokens as $token)
            @if( $token->id === $serviceToken->id) {{-- Skip the current token --}}
                @continue
            @endif
            <tr id="token_{{$token->id}}">
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{$token->id}}" id="token_{{$token->id}}" name="pending_token_ids[]" 
                        {{ in_array($token->id, (old('pending_token_ids') ?? $serviceMyTask?->pendingServiceTokens?->pluck('service_token_id')?->toArray())??[]) ? 'checked' : '' }}>
                    </div>
                </td>
                <td>{{ $token->product?->name }}</td>
                <td>{{ $token->serial_number??'N/A' }}</td>
                <td>{{ $token?->engineerAssign?->engineers?->pluck('full_name')->join(', ')}}</td>
                <td>{{ $token->service_type }}</td>
            </tr>
            <tr>
                <td colspan="5">
                    <label for="description_{{ $token->id }}">Description</label>
                    <textarea name="pending_descriptions[{{ $token->id }}]" id="description_{{ $token->id }}" class="form-control" rows="3" {{ in_array($token->id, (old('pending_token_ids') ?? $serviceMyTask?->pendingServiceTokens?->pluck('service_token_id')?->toArray())??[]) ? '' : 'disabled' }}>{{
                    old('pending_descriptions.' . $token->id, $serviceMyTask?->pendingServiceTokens?->firstWhere('service_token_id', $token->id)?->description ?? '')
                    }}</textarea>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


@push('script')
    <script>
        $(document).ready(function () {
            $('input[type="checkbox"]').click(function () {
                let token_id = $(this).attr('id').replace('token_', '');
                let description = $('#description_' + token_id);
                if ($(this).is(':checked')) {
                    description.removeAttr('disabled');
                    // Add visual indicator for required field if needed
                    if (description.attr('required') !== 'required') {
                        description.attr('required', 'required');
                    }
                } else {
                    description.attr('disabled', 'disabled');
                    description.removeAttr('required');
                }
            });
        });
    </script>

@endpush