<form action="{{ route('access_control.roles.add_user', $role->id) }}" method="post">
    @csrf
    Add Users to {{ $role->name }}
    <div class="row">
        <div class="row mb-3">
            <div class="mb-3">
                <label for="" class="form-label">Users</label>
                <select class="form-select tom-select" name="users[]" multiple>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" data-avater="{{ optional($user->profile)->avater }}"
                            data-email="{{ $user->email }}" @if (in_array($user->id, $role->users->pluck('id')->toArray())) selected @endif>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- submit --}}
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        </div>
</form>
