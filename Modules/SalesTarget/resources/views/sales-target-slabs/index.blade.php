// resources/views/sales-target-slabs/index.blade.php
@section('title', 'Sales Target Slab')
@section('description', 'Sales Target Slab')
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
                                    <li class="breadcrumb-item active" aria-current="page">Sales Target Slabs</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">Sales Target Slabs </h4>
                    <x-error-alart />
                </div>
            
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-12">
                            <div class="mt-40 mb-50 p-30">
                                <div class="card-body">
                                    <h5>Add New Slab</h5>
                                    <form action="{{ route('sales_target.sales-target-slabs.store') }}" method="POST">
                                        @csrf
                                        <div class="row g-2">
                                            <div class="col-md-3">
                                                <label>Name</label>
                                                <input type="text" name="name" class="form-control" placeholder="Slab No">
                                            </div>
                                            <div class="col-md-2">
                                                <label>Min Salary</label>
                                                <input type="number" step="0.01" name="min_salary" class="form-control" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Max Salary</label>
                                                <input type="number" step="0.01" name="max_salary" class="form-control" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Multiplier</label>
                                                <input type="number" step="0.01" name="target_multiplier" class="form-control" required>
                                            </div>
                                            <div class="col-md-3 pt-3 align-items-end">
                                                @if (hasPermission('sales_target.sales-target-slabs.create')) 
                                                    <button type="submit" class="btn btn-primary w-100" onclick="return confirmSubmit(event, this, 'Save');">Add Slab</button>
                                                @endif
                                            </div>
                                        </div>
                                    </form>

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Salary Range</th>
                                                <th>Multiplier</th>
                                                <th>Active</th> 
                                                <th colspan="2">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($slabs as $slab)
                                            <tr> 


                                                <tr>
                                                    <td>
                                                        <input type="text"  name="name" value="{{ $slab->name }}" class="form-control form-control-sm" form="update-slab-{{ $slab->id }}">
                                                    </td>
                                                    <td>
                                                        <input type="number"   step="0.01"   name="min_salary"    value="{{ $slab->min_salary }}"     class="form-control form-control-sm d-inline"   style="width:300px"   form="update-slab-{{ $slab->id }}">
                                                        -
                                                        <input type="number"   step="1000"  name="max_salary" value="{{ $slab->max_salary }}" class="form-control form-control-sm d-inline"  style="width:300px" form="update-slab-{{ $slab->id }}">
                                                    </td>

                                                    <td>
                                                        <input type="number"  step="0.01"  name="target_multiplier"  value="{{ $slab->target_multiplier }}" class="form-control form-control-sm"  style="width:200px"  form="update-slab-{{ $slab->id }}">
                                                    </td>

                                                    <td>
                                                        <select name="is_active"   class="form-control form-control-sm"  form="update-slab-{{ $slab->id }}">
                                                            <option value="1" {{ $slab->is_active == 1 ? 'selected' : '' }}>
                                                                Active
                                                            </option>
                                                            <option value="0" {{ $slab->is_active == 0 ? 'selected' : '' }}>
                                                                Inactive
                                                            </option>
                                                        </select>
                                                    </td>

                                                    <td class="text-center">

                                                        <form id="update-slab-{{ $slab->id }}" action="{{ route('sales_target.sales-target-slabs.update', $slab->id) }}"  method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            @if (hasPermission('sales_target.sales-target-slabs.update')) 
                                                                <button type="submit"  class="btn btn-xs btn-outline-warning" onclick="return confirmSubmit(event, this, 'Update');">
                                                                    <i class="far fa-edit"></i>
                                                                </button>
                                                             @endif
                                                        </form>

                                                    </td>
                                                   
                                               
                                                    <td> 
                                                        <form action="{{ route('sales_target.sales-target-slabs.destroy', $slab->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            @if (hasPermission('sales_target.sales-target-slabs.destroy')) 
                                                                <button type="submit" class="btn btn-xs btn-outline-danger" onclick="return confirmSubmit(event, this, 'Delete');"> <i class="far fa-trash-alt"></i></button>
                                                            @endif
                                                        </form>
                                                    </td>
                                              
                                                   
                                            </tr>
                                            @empty
                                            <tr><td colspan="5" class="text-center">No Slab Found</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
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
       function confirmSubmit(event, button, status) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to '+status+' this information?',
                icon: 'warning',
                showCancelButton: true, 
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, '+status,
                reverseButtons: true
            }).then((result) => {

                if (result.isConfirmed) {
                    // Confirmation দেওয়ার পর form submit হবে
                    button.closest('form').submit();
                }

            });

            return false;
        }
    </script> 

@endSection

