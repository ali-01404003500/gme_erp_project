@section('title', 'Noticeboard List')
@section('description', 'Noticeboard List')
@extends('layout.app')
@section('content')

    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.noticeboards-list-menu-title') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('hrm.noticeboards.create'))
                                    <a href="{{ route('hrm.noticeboards.create') }}" class="btn px-20 btn-primary btn-sm">
                                        <i class="las la-plus fs-16"></i>Add New
                                    </a>
                                @endif
                                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                    class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <style>
                .nav-icon la la-cart-arrow-down {
                    font-size: 26px;
                }

                .noticeboard-table-custom,
                .noticeboard-table-custom th,
                .noticeboard-table-custom td {
                    border: 1px solid #dee2e6 !important;
                    border-collapse: collapse !important;
                }

                .noticeboard-table-custom th,
                .noticeboard-table-custom td {
                    padding: 12px;
                    vertical-align: middle;
                }

                .noticeboard-table-custom thead th {
                    background-color: #f8f9fa;
                    border-bottom-width: 2px !important;
                }

                .table thead th {
                    background-color: #35526e !important;
                    color: #ffffff !important;
                    font-weight: 600 !important;
                    text-transform: uppercase;
                    font-size: 0.85rem !important;
                    letter-spacing: 0.08em;
                    border-bottom: 2px solid #2a4054 !important;
                    padding: 14px 16px !important;
                    vertical-align: middle;
                    text-align: center;
                }
            </style>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.noticeboards-list-menu-title') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td class="text-center">
                                                <input type="text" class="form-control" name="title"
                                                    value="{{ request('title') }}" placeholder="Title" />
                                            </td>
                                            <td class="text-center">
                                                <select name="notice_type_id" class="form-control">
                                                    <option value="">Select Type</option>
                                                    @foreach ($noticeTypes as $noticeType)
                                                        <option value="{{ $noticeType->id }}" {{ (request('notice_type_id') == $noticeType->id) ? 'selected' : '' }}>
                                                            {{ $noticeType->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control flatdate" name="fromDate"
                                                        value="{{ request('fromDate') }}" autocomplete="off"
                                                        placeholder="From Date" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-exchange-alt"></i>
                                                    </span>

                                                    <input type="text" class="form-control flatdate" name="toDate"
                                                        value="{{ request('toDate') }}" autocomplete="off"
                                                        placeholder="To Date" />
                                                </div>
                                            </td>
                                            <td colspan="5" class="text-right">
                                                <div class="btn-group btn-corner">
                                                    <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i>
                                                        Search</button>
                                                    <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i
                                                            class="fa fa-refresh"></i> Refresh</a>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table noticeboard-table-custom" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Publish Date</th>
                                        <th>Expire Date</th>
                                        <th>Status</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($noticeBoards as $key => $value)
                                        <tr>
                                            <td class="text-center">
                                                {{ ($noticeBoards->currentPage() - 1) * $noticeBoards->perPage() + $loop->iteration  }}
                                            </td>
                                            <td>{{ $value->title }}</td>
                                            <td>{{ $value->noticeType->name }}</td>
                                            <td>{{ $value->publish_date }}</td>
                                            <td>{{ $value->expire_date }}</td>
                                            <td><span class="badge badge-round badge-info">{{ $value->status }}</span></td>

                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if (hasPermission('hrm.noticeboards.update'))
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('hrm.noticeboards.edit', $value->id) }}" title="Edit"><i
                                                                class="far fa-edit"></i></a>
                                                    @endif

                                                    @if (hasPermission('hrm.noticeboards.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('hrm.noticeboards.destroy', $value->id) }}"
                                                            class="btn btn-outline-danger delete-confirm" title="Delete"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    @endif
                                                    @if (hasPermission('hrm.noticeboards.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('hrm.noticeboards.show', $value->id) }}"><i
                                                                class="fas fa-eye"></i></a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-none">
                                <form class="delete-form" action="" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('page_scripts')
    <script>
        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });

        $(".timePicker").datetimepicker({
            format: 'HH:mm',
            autoclose: true
        });
    </script>
@endsection