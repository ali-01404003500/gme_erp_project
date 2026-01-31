@section('title', 'Holiday Types')
@section('description', 'Holiday Types')
@extends('layout.app')
@section('content')
    <!-- CONTENT AREA -->
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.hrm-settings-holiday-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('hrm.settings.holidays.create'))
                                    <a href="{{ route('hrm.settings.holidays.create') }}" class="btn btn-sm btn-primary"> Add New</a>
                                    {{-- <button class="btn btn-xs btn-primary me-1" data-bs-toggle="modal"
                                        data-bs-target="#createModal">
                                        Add New
                                    </button> --}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.hrm-settings-holiday-menu-title') }}
                            </h4>
                            <x-error-alart />
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover"
                                data-page='@include('utils.table_paginate', ['data' => $holidays])' style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 8%">Sl</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Holiday Day Type</th>
                                        <th class="text-center">Days</th>
                                        <th class="text-center">Repeat Every Year</th>
                                        <th class="text-center no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($holidays as $key => $holiday)
                                        <tr>
                                        <td class="text-center">{{ ($holidays->currentPage() - 1) * $holidays->perPage() + $loop->iteration  }}</td>
                                            <td class="text-center">{{ $holiday->name }}</td>
                                            @if( $holiday->day_type == 1) 
                                            <td class="text-center">
                                                {{ 'Holiday' }}
                                               
                                            </td>
                                            <td class="text-center">{{ $holiday->start_date ? date('d-m-Y', strtotime($holiday->start_date)) : '' }} to {{ $holiday->end_date ? date('d-m-Y', strtotime($holiday->end_date)) : '' }}</td>

                                            @elseif( $holiday->day_type == 2) 
                                                <td class="text-center">  {{ 'Weekend' }}</td>
                                                <td class="text-center">{{ $holiday->day_name }}</td>
                                            @endif
                                            <td class="text-center">{{ $holiday->every_year ? 'Yes' : 'No' }}</td>
                                            

                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if (hasPermission('hrm.settings.holidays.update'))
                                                        
                                                        <a class="btn btn-outline-warning"
                                                        href="{{ route('hrm.settings.holidays.edit', $holiday->id) }}"
                                                        title="Edit"><i class="far fa-edit"></i></a>                                                    
                                                    @endif



                                                    @if (hasPermission('hrm.settings.holidays.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('hrm.settings.holidays.destroy', $holiday->id) }}"
                                                            class="btn btn-outline-danger delete-confirm" title="Delete"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    @endif

                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <form class="delete-form d-none" action="" method="POST">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>

                </div>

                </div>

                <!-- Create Modal -->
                <div class="modal fade inputForm-modal" id="createModal" tabindex="-1" role="dialog"
                    aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">

                            <div class="modal-header" id="createModalLabel">
                                <h5 class="modal-title">{{ trans('menu.hrm-settings-holiday-create-menu-title') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <form action="{{ route('hrm.settings.holidays.store') }}" method="post">
                                @csrf
                                <div class="modal-body">

                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Holiday Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="name" class="form-control"
                                                placeholder=" Holiday Name *" required>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Start Date</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="start_date" class="form-control flatdate"
                                                placeholder=" Start Date *" required>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">End Date</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="end_date" class="form-control flatdate"
                                                placeholder=" End Date *" required>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Repeat Yearly</label>
                                        <div class="col-sm-12">
                                            <select class="form-control" name="every_year" required>
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade inputForm-modal" id="editModal" tabindex="-1" role="dialog"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header" id="editModalLabel">
                    <h5 class="modal-title">Edit </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="editFrom">
                    @csrf
                    @method('put')
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Holiday Name</label>
                            <div class="col-sm-12">
                                <input type="text" name="name" class="form-control" placeholder=" Holiday Name *"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Start Time</label>
                            <div class="col-sm-12">
                                <input type="text" name="start_date" class="form-control" placeholder=" Start Time *"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">End Time</label>
                            <div class="col-sm-12">
                                <input type="text" name="end_date" class="form-control" placeholder=" End Time *"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Repeat Yearly</label>
                            <div class="col-sm-12">
                                <select class="form-control" name="every_year" required>
                                    <option value="">Select</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
<!-- CONTENT AREA -->
@section('page_scripts')

    <script>
        $(document).ready(function(e) {
            $(document).on('click', '.btn-edit', function() {
                const data = $(this).data('data');
                //loop through data object
                $.each(data, function(key, value) {
                    if (key == 'start_date' || key == 'end_date') {
                        value = new Date(value).toLocaleDateString();
                    }
                    $('#editModal input[name="' + key + '"]').val(value);
                    $('#editModal select[name="' + key + '"] option[value="' + value + '"]').prop(
                        'selected', true);

                })
                $("#editFrom").attr("action", $(this).data('action'));
            });
        });

        // function edit(element) {
        //     let name = $(element).data('name');
        //     let code = $(element).data('code');
        //     let action = $(element).data('action');
        //     $('#name').val(name);
        //     $('#code').val(code);
        //     $("#editFrom").attr("action", action);
        // }
    </script>
@endsection

