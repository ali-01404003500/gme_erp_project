@section('title',"Employee List")
@section('description',"Employee List")
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
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.employee-list-menu-title') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center"> 
                            <button type="button" class="btn btn-xs btn-success btn-sm me-2 ml-5" data-bs-toggle="modal" style="margin-left: 5px;"
                                data-bs-target="#importEmployeeModal">
                                <i class="las la-file-import fs-16"></i> Import CSV
                            </button>
                            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                <i class="las la-file-pdf fs-16"></i> PDF
                            </a> 
                            @if (hasPermission('hrm.employees.create'))
                            <a href="{{ route('hrm.employees.create') }}" class="btn px-20 btn-primary btn-sm mr-5" style="margin-left: 5px;">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                            @endif
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.employee-list-menu-title') }}</h4>
            </div>
            <div class="col-md-12">
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%">
                                                <select name="full_name" id="full_name" class="form-control tom-select"
                                                    data-placeholder="Select Employee Name">
                                                    <option value=""></option>
                                                    @foreach ($employeeSearch as $key => $value)
                                                        <option {{ request('full_name') == $value->full_name ? 'selected' : '' }}
                                                            value="{{ $value->full_name }}">
                                                            {{ $value->full_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td width="16%">
                                                <select name="department" id="department"  class="form-control tom-select"  data-placeholder="Select Department">
                                                    <option value=""></option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                                            {{ $department->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td width="16%">
                                                <select name="designation" id="designation" class="form-control tom-select"
                                                    data-placeholder="Select Designation">
                                                    <option value=""></option>
                                                    @foreach ($designations as $designation)
                                                        <option value="{{ $designation->id }}" {{ request('designation') == $designation->id ? 'selected' : '' }}>
                                                            {{ $designation->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td width="16%">
                                                <select name="branch" id="branch" class="form-control tom-select"
                                                    data-placeholder="Select Branch">
                                                    <option value=""></option>
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>
                                                            {{ $branch->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td> 
                                            <td width="16%">
                                                <select name="status" id="status" class="form-control tom-select">
                                                    <option value="" {{ request('status') == 0 ? 'selected' : '' }}></option> 
                                                    <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ request('status') == 0 ? 'selected' : '' }}>Inactive</option>
                                                    
                                                </select>
                                            </td> 

                                            <td colspan="5" class="text-right" width="16%">
                                                <div class="btn-group btn-corner">
                                                    <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i>Search</button>
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
                <div class="card mb-4">
                    <div class="card-body">
                        <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $employees])' style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Date of Birth</th>
                                    <th>Joining Date</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Status</th>
                                    <th class="no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr>
                                    <td>name</td>
                                    <td>email</td>
                                    <td class="no-content">Action</td>
                                </tr> --}}
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>{{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration  }}</td>
                                        <td>{{ $employee->employementDetail->card_no }}</td>
                                        <td>
                                            <a class="text-dark fw-500" href="{{ route('hrm.employees.show', $employee->id) }}">
                                                {{ $employee->full_name }}
                                            </a>
                                        </td>
                                        {{-- <td>{{ $employee->full_name }}</td> --}}
                                        <td>{{ $employee->employementDetail->department->name ?? 'N/A' }}</td>
                                        <td>{{ $employee->employementDetail->designation->name ?? 'N/A' }}</td>
                                        
                                        <td>{{ $employee->date_of_birth }}</td>
                                        <td>{{ $employee->employementDetail->date_of_joining }}</td>
                                        <td>{{ $employee->email_address }}</td>
                                        <td>{{ $employee->personal_mobile }}</td>
                                        <td>{{ $employee->status == 1 ? 'Active' : 'Inactive' }}</td>
 


                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                @if (hasPermission('hrm.employees.update'))
                                                    <a class="btn btn-outline-warning" href="{{ route('hrm.employees.edit', $employee->id) }}">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                @endif
            
                                                {{-- @if (hasPermission('users.update'))
                                                    <a href="{{ route('hrm.employees.edit_password', $employee->id) }}"
                                                        class="btn btn-outline-info">
                                                        <i class="fas fa-key"> </i>
                                                    </a>
                                                @endif --}}
            
                                                @if (hasPermission('hrm.employees.destroy'))
                                                    <button type="button" data-action="{{ route('hrm.employees.destroy', $employee->id) }}"
                                                        class="btn btn-outline-danger delete-confirm">
                                                        <i class="far fa-trash-alt"></i></button>
                                                @endif
            
                                                @if (hasPermission('hrm.employees.show'))
                                                    <a class="btn btn-outline-primary" href="{{ route('hrm.employees.show', $employee->id) }}"><i class="fas fa-eye"></i></a>
                                                @endif
                                                @if (hasPermission('hrm.employee-salarys.create'))
                                                <a class="btn btn-outline-success" href="{{ route('hrm.employee-salarys.create') }}?employee_id={{ $employee->id }}"><i class="fas fa-money-bill"></i></a>

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

    <div class="modal fade inputForm-modal" id="importEmployeeModal" tabindex="-1" role="dialog"
    aria-labelledby="importEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header" id="importEmployeeModalLabel">
                <h5 class="modal-title">Import Employees from CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('hrm.employees.import') }}" method="post" id="importFrom"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-4">
                        <label for="csv_file" class="col-sm-12 col-form-label">CSV File</label>
                        <div class="col-sm-12">
                            <input type="file" name="csv_file" id="csv_file" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-sm-12">
                            <a href="{{ route('hrm.employees.download-template') }}" class="btn btn-info">Download Sample CSV</a>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
