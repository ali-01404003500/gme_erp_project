@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class=" alert alert-danger alert-dismissible fade show my-4" role="alert">
            <div class="alert-content">
                <strong>{{ $error }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endforeach
@endif
