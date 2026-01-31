@if ($data->lastPage() > 1)
<div class="d-flex justify-content-end mt-3">
    <ul class="pagination custom-pagination mb-0">
        {{-- First --}}
        <li class="page-item @if($data->currentPage() == 1) disabled @endif">
            <a class="page-link" href="{{ $data->appends(request()->query())->url(1) }}">
                First
            </a>
        </li>

        {{-- Previous --}}
        <li class="page-item @if($data->currentPage() == 1) disabled @endif">
            <a class="page-link" href="{{ $data->appends(request()->query())->previousPageUrl() }}">
                <i class="fa fa-angle-left"></i>
            </a>
        </li>

        {{-- Page Numbers --}}
        @foreach(range(1, $data->lastPage()) as $i)
            @if($i >= $data->currentPage() - 3 && $i <= $data->currentPage() + 3)
                @if ($i == $data->currentPage())
                    <li class="page-item active">
                        <span class="page-link">{{ $i }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $data->appends(request()->query())->url($i) }}">
                            {{ $i }}
                        </a>
                    </li>
                @endif
            @endif
        @endforeach

        {{-- Next --}}
        <li class="page-item @if($data->currentPage() == $data->lastPage()) disabled @endif">
            <a class="page-link" href="{{ $data->appends(request()->query())->nextPageUrl() }}">
                <i class="fa fa-angle-right"></i>
            </a>
        </li>

        {{-- Last --}}
        <li class="page-item @if($data->currentPage() == $data->lastPage()) disabled @endif">
            <a class="page-link" href="{{ $data->appends(request()->query())->url($data->lastPage()) }}">
                Last
            </a>
        </li>
    </ul>
</div>
@endif
<style>
    /* Custom Pagination Design */
.custom-pagination .page-link {
    border-radius: 8px;
    margin: 0 3px;
    color: #555;
    border: 1px solid #dee2e6;
    padding: 6px 12px;
    font-size: 14px;
    transition: all 0.2s ease-in-out;
}

.custom-pagination .page-link:hover {
    background-color: #4472C4;
    color: #fff;
    border-color: #4472C4;
}

.custom-pagination .page-item.active .page-link {
    background-color: #4472C4;
    border-color: #4472C4;
    color: #fff;
    font-weight: 600;
}

.custom-pagination .page-item.disabled .page-link {
    color: #aaa;
    background-color: #f8f9fa;
    cursor: not-allowed;
}

.custom-pagination .page-link i {
    font-size: 13px;
}

</style>