 
@section('title', 'Sales Incentive Slab')
@section('description', 'Sales Incentive Slab')
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
                                    <li class="breadcrumb-item active" aria-current="page">Sales Incentive Slabs</li>
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
                    <h4 class="text-capitalize breadcrumb-title">Sales Incentive Slabs </h4>
                    <x-error-alart />
                </div>
            
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-12">
                            <div class="mt-40 mb-50 p-30">
                                <div class="card-body">
                                    <h5>Add New Slab</h5>
                                    <form action="{{ route('sales_target.sales-incentive-slabs.store') }}" method="POST">
                                        @csrf
                                        <div class="row g-2">
                                            <div class="col-md-3">
                                                <label>Min Achievement %</label>
                                                <input type="number" step="0.01" name="min_percent" class="form-control" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Max Achievement % (blank = no limit)</label>
                                                <input type="number" step="0.01" name="max_percent" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Incentive Rate %</label>
                                                <input type="number" step="0.01" name="rate_percent" class="form-control" required>
                                            </div>
                                            @if (hasPermission('sales_target.sales-incentive-slabs.create')) 
                                                <div class="col-md-3 d-flex align-items-end pt-3">
                                                    <button type="submit" class="btn btn-primary w-100">Add</button>
                                                </div>
                                            @endif
                                        </div>
                                    </form>

                                    <table class="table table-bordered mt-3">
                                        <thead>
                                            <tr>
                                                <th>Achievement Range</th>
                                                <th>Rate</th>
                                                <th>Active</th> 
                                                <th colspan="2">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($tiers as $tier)
                                            <tr>  
                                                <tr> 
                                                    <td>
                                                        <input type="number"   step="0.01"   name="min_percent"    value="{{ $tier->min_percent }}"     class="form-control form-control-sm d-inline"   style="width:300px"   form="update-slab-{{ $tier->id }}">
                                                        -
                                                        <input type="number"   step="1000"  name="max_percent" value="{{ $tier->max_percent }}" class="form-control form-control-sm d-inline"  style="width:300px" form="update-slab-{{ $tier->id }}">
                                                    </td>

                                                    <td>
                                                        <input type="number"  step="0.01"  name="rate_percent"  value="{{ $tier->rate_percent }}" class="form-control form-control-sm"  style="width:200px"  form="update-slab-{{ $tier->id }}">
                                                    </td>

                                                    <td>
                                                        <select name="is_active"   class="form-control form-control-sm"  form="update-slab-{{ $tier->id }}">
                                                            <option value="1" {{ $tier->is_active == 1 ? 'selected' : '' }}>
                                                                Active
                                                            </option>
                                                            <option value="0" {{ $tier->is_active == 0 ? 'selected' : '' }}>
                                                                Inactive
                                                            </option>
                                                        </select>
                                                    </td>

                                                    <td class="text-center">
                                                        @if (hasPermission('sales_target.sales-incentive-slabs.update')) 
                                                            <form id="update-slab-{{ $tier->id }}" action="{{ route('sales_target.sales-incentive-slabs.update', $tier->id) }}"  method="POST">
                                                                @csrf
                                                                @method('PUT')

                                                                <button type="submit"  class="btn btn-xs btn-outline-warning" onclick="return confirmSubmit(event, this, 'Update');">
                                                                    <i class="far fa-edit"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                   
                                                @if (hasPermission('sales_target.sales-incentive-slabs.destroy')) 
                                                    <form action="{{ route('sales_target.sales-incentive-slabs.destroy', $tier->id) }}" method="POST" class="d-inline">
                                                        <td> 
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-xs btn-outline-danger  " onclick="return confirmSubmit(event, this, 'Delete');"> <i class="far fa-trash-alt"></i></button>
                                                        </td>
                                                    </form>
                                                @endif
                                                   
                                            </tr>
                                            @empty
                                            <tr><td colspan="5" class="text-center">No Incentive Slab Found</td></tr>
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

