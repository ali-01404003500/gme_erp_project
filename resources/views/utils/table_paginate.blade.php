{{-- Company: Opzo. --}}
{{-- Author: Md Shadhin --}}
{{-- Developer: Md Shadhin --}}
{{-- Copywrite: 2024 --}}

@if ($data->lastPage() > 1)
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item @if ($data->appends(request()->query())->currentPage() == 1) disabled @endif">
                <a class="page-link" title="First"
                    @if ($data->appends(request()->query())->currentPage() == 1) href="javascript:void(0)"
            @else
                href="{{ $data->appends(request()->query())->url(1) }}" @endif>
                    <i class="fa fa-angle-double-left">
                    </i></a>
            </li>

            <li class="page-item @if ($data->appends(request()->query())->currentPage() == 1) disabled @endif">
                <a class="page-link" title="Previous" href="{{ $data->appends(request()->query())->previousPageUrl() }}"><i
                        class="fa fa-angle-left"></i></a>
            </li>
            @foreach (range(1, $data->appends(request()->query())->lastPage()) as $i)
                @if (
                    $i >= $data->appends(request()->query())->currentPage() - 2 &&
                        $i <= $data->appends(request()->query())->currentPage() + 2)
                    @if ($i == $data->appends(request()->query())->currentPage())
                        <li class="page-item  active" disabled><a href="javascript:void(0)"
                                class="page-link active">{{ $i }}</a></li>
                    @else
                        <li class="page-item"><a class="page-link"
                                href="{{ $data->appends(request()->query())->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endif
            @endforeach

            <li class="page-item @if ($data->appends(request()->query())->lastPage() == $data->appends(request()->query())->currentPage()) disabled @endif">
                <a class="page-link" title="Next" href="{{ $data->appends(request()->query())->nextPageUrl() }}"><i
                        class="fa fa-angle-right"></i></a>
            </li>
            <li class="page-item @if ($data->appends(request()->query())->lastPage() == $data->appends(request()->query())->currentPage()) disabled @endif">
                <a class="page-link"
                    @if ($data->appends(request()->query())->lastPage() == $data->appends(request()->query())->currentPage()) )
                href="javascript:void(0)" class="page-link" title="Last"
            @else
                class="page-link" title="Last"
                href="{{ $data->appends(request()->query())->url($data->lastPage()) }}" @endif>
                    <i class="fa fa-angle-double-right"></i>
                </a>
            </li>
        </ul>

    </nav>
@endif
