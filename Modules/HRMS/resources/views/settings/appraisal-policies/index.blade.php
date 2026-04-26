@section('title', 'Appraisal Policies')
@section('description', 'Appraisal Policies')
@extends('layout.app')
@section('content')
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
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans(' Appraisal Policies') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('hrm.settings.appraisal-policies.create'))
                                <button class="btn btn-xs btn-primary me-1" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                Add New
                            </button>
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
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Appraisal Policies') }}</h4>
                        </div>
                    </div>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table table-bordered dt-table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 8%">Sl</th>
                                        <th class="text-center">Appraisal Policy</th>
                                        <th class="text-center">Eligibelity</th>
                                        <th class="text-center no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @csrf
                                    @foreach ($appraisalPolicys as $key => $item)
                                        <tr>
                                        <td class="text-center">{{ ($appraisalPolicys->currentPage() - 1) * $appraisalPolicys->perPage() + $loop->iteration  }}</td>
                                            <td class="text-center">{{ @$item->designation->name }}</td>
                                            <td class="text-center">{{ $item->period }} {{ $item->period_type }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if (hasPermission('hrm.settings.appraisal-policies.update'))
                                                        <a href={{ $item->id }} class="btn btn-edit  btn-outline-warning"
                                                            data-designation_id="{{ $item->designation_id }}" data-period="{{ $item->period }}" data-period_type="{{ $item->period_type }}"
                                                            data-action="{{ route('hrm.settings.appraisal-policies.update', $item->id) }}"
                                                            data-toggle="tooltip" data-placement="top" title="Edit"
                                                            data-bs-toggle="modal" data-bs-target="#editModal">
                                                            <i class="far fa-edit"></i>
                                                        </a>
                                                    @endif
                                                    @if (hasPermission('hrm.settings.appraisal-policies.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('hrm.settings.appraisal-policies.destroy', $item->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i></button>
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

                <!-- Create Modal -->
                <div class="modal fade inputForm-modal" id="createModal" tabindex="-1" role="dialog"
                    aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">

                            <div class="modal-header" id="createModalLabel">
                                <h5 class="modal-title">Add Appraisal Policy</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <form action="{{ route('hrm.settings.appraisal-policies.store') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Designation</label>
                                        <div class="col-sm-12">
                                            <select name="designation_id" class="form-select tom-select" required>
                                                <option value="">Select Designation</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}">{{ $designation->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('designation_id')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="inputError" class="col-sm-3 control-label bolder">
                                            Period</label>
                                        <div class="col-xs-12 col-sm-8">
                                            <input type="number" name="period" class="form-control" required>
                                            @error('period')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>      
                                    </div>
                                    <div class="row mb-4">
                                        <label for="inputError" class="col-sm-3 control-label bolder">
                                            Period Type</label>
                                        <div class="col-xs-12 col-sm-8">
                                            <select name="period_type" class="form-select tom-select" required>
                                                <option value="">Select Period Type</option>
                                                <option value="Day">Day</option>
                                                <option value="Month">Month</option>
                                                <option value="Year">Year</option>
                                            </select>
                                            @error('period_type')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
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
                    <h5 class="modal-title">Edit Appraisal Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="editFrom">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Designation</label>
                            <div class="col-sm-12">
                                <select name="designation_id" class="form-select tom-select" required>
                                    <option value="">Select Designation</option>
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation->id }}">{{ $designation->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('designation_id')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="inputError" class="col-sm-3 control-label bolder">
                                Period</label>
                            <div class="col-xs-12 col-sm-8">
                                <input type="number" name="period" class="form-control" id="period" required>
                                @error('period')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror        
                            </div>  
                        </div>
                        <div class="row mb-4">
                            <label for="inputError" class="col-sm-3 control-label bolder">Period Type</label>
                            <div class="col-xs-12 col-sm-8">
                                <select name="period_type" class="form-select tom-select" required>
                                    <option value="">Select Period Type</option>
                                    <option value="Day">Day</option>
                                    <option value="Month">Month</option>
                                    <option value="Year">Year</option>
                                </select>
                                @error('period_type')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
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
    </div>
@endsection
<!-- CONTENT AREA -->
@section('page_scripts')

    <script>
         $(document).ready(function(e) {
            $(document).on('click', '.btn-edit', function() {
                $('#period').val($(this).data('period'));
                $("#editModal select[name=period_type]").prop('tomselect')?.setValue($(this).data('period_type'));
                $("#editModal select[name=designation_id]").prop('tomselect')?.setValue($(this).data('designation_id'));
                $("#editFrom").attr("action", $(this).data('action'));

            });
        });

      
    </script>
@endsection
