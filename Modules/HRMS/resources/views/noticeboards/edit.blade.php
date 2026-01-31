@section('title', 'Leave Application')
@section('description', 'Leave Application')
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
                                        {{ trans('menu.create-noticeboards-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('hrm.noticeboards.index'))
                            <a href="{{ route('hrm.noticeboards.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                    class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.create-noticeboards-menu-title') }}
                    </h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-12">
                            <div class="mt-40 mb-50 p-30">
                                <form action="{{ route('hrm.noticeboards.update', $noticeBoard->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-25">
                                                <label for="title" class="color-dark fs-14 fw-500 align-center">Title <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="{{ old('title', $noticeBoard->title) }}" name="title" id="title" placeholder="Title" required>
                                                @if ($errors->has('title'))
                                                    <p class="text-danger">{{ $errors->first('title') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-25">
                                                <label for="type" class="color-dark fs-14 fw-500 align-center">Type <span class="text-danger">*</span></label>
                                                <select name="notice_type_id" id="type" class="form-control tom-select" required>
                                                    <option value="">Select Type</option>
                                                    @foreach ($noticeTypes as $noticeType)
                                                        <option value="{{ $noticeType->id }}" {{ (old('type', $noticeBoard->notice_type_id) == $noticeType->id) ? 'selected' : '' }}>{{ $noticeType->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('type'))
                                                    <p class="text-danger">{{ $errors->first('type') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-25">
                                                <label for="publish_date" class="color-dark fs-14 fw-500 align-center">Publish Date <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control flatdate" value="{{ old('publish_date', $noticeBoard->publish_date) }}" name="publish_date" id="publish_date" placeholder="Publish Date" required>
                                                @if ($errors->has('publish_date'))
                                                    <p class="text-danger">{{ $errors->first('publish_date') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-25">
                                                <label for="publish_time" class="color-dark fs-14 fw-500 align-center">Publish Time <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control flattime" value="{{ old('publish_time', $noticeBoard->publish_time) }}" name="publish_time" id="publish_time" placeholder="Publish Time" required>
                                                @if ($errors->has('publish_time'))
                                                    <p class="text-danger">{{ $errors->first('publish_time') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-25">
                                                <label for="expire_date" class="color-dark fs-14 fw-500 align-center">Expire Date <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control flatdate" value="{{ old('expire_date', $noticeBoard->expire_date) }}" name="expire_date" id="expire_date" placeholder="Expire Date" required>
                                                @if ($errors->has('expire_date'))
                                                    <p class="text-danger">{{ $errors->first('expire_date') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group mb-25">
                                                <label for="description" class="color-dark fs-14 fw-500 align-center">Description <span class="text-danger">*</span></label>
                                                <textarea name="description" id="description" class="form-control" placeholder="Description" required>{{ old('description', $noticeBoard->description) }}</textarea>
                                                @if ($errors->has('description'))
                                                    <p class="text-danger">{{ $errors->first('description') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        

                                        <div class="col-md-4">
                                            <div class="form-group mb-25">
                                                <label for="status" class="color-dark fs-14 fw-500 align-center">Status <span class="text-danger">*</span></label>
                                                <select name="status" id="status" class="form-control" required>
                                                    <option value="">Select Status</option>
                                                    <option value="Published" {{ (old('status', $noticeBoard->status) == 'Published') ? 'selected' : '' }}>Published</option>
                                                    <option value="Unpublished" {{ (old('status', $noticeBoard->status) == 'Unpublished') ? 'selected' : '' }}>Unpublished</option>
                                                </select>
                                                @if ($errors->has('status'))
                                                    <p class="text-danger">{{ $errors->first('status') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit"
                                            class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                                    </div>
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
        $(document).ready(function() {
            $('#from_date, #to_date').on('change', getTotalDays);
            $('#from_date, #to_date, #employee_id, #leave_type').on('change', loadResponse);

            function getTotalDays() {
                let start = $('#from_date').val();
                let end = $('#to_date').val();

                if (start && end) {
                    let startDay = new Date(start);
                    let endDay = new Date(end);

                    let millisecondsPerDay = 1000 * 60 * 60 * 24;
                    let millisBetween = endDay.getTime() - startDay.getTime();
                    let days = millisBetween / millisecondsPerDay;

                    let leave_days = Number((days+1) | 0)
                    $("#total_days").val(leave_days);
                }
            }

            function loadResponse() {
                let employee = $('#employee_id').val();
                let leave_type = $('#leave_type').val();

                if (leave_type) {
                    let publish_time = new Date();
                    publish_time = publish_time.toISOString().slice(0,19).replace('T', ' ');
                    $.get('{{ route('hrm.get.leave.response') }}?employee=' + employee + '&leave_type=' + leave_type + '&publish_time=' + publish_time, function(res) {
                        $(".typeWiseData").show();
                        $("#companyTotalLeave").val(res.companyLeaveType.total_day);
                        $("#simultaneouslyLimit").val(res.companyLeaveType.simultaneously_limit);
                        $("#leaveBalance").val(res.leaveBalance);
                    });
                }
            }
        });
    </script>
@endSection
