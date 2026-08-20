
@section('title', 'Performance Based Salary')
@section('description', 'Performance Based Salary Slab')
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
                                    <li class="breadcrumb-item active" aria-current="page">Performance Based Salary Slabs</li>
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
                    <h4 class="text-capitalize breadcrumb-title">Performance Based Salary Slabs </h4>
                    <x-error-alart />
                </div>
            
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-12">
                            <div class="mt-40 mb-50 p-30">
                                <div class="card-body">
                                    <h5>Add New Slab</h5>
                                        <form action="{{ route('sales_target.sales-salary-brackets.store') }}" method="POST">
                                            @csrf
                                            <div class="row g-2">
                                                <div class="col-md-2"><label>Min Achievement %</label>
                                                    <input type="number" step="1" name="min_percent" class="form-control" required></div>
                                                <div class="col-md-2"><label>Max Achievement %</label>
                                                    <input type="number" step="1" name="max_percent" class="form-control"></div>
                                                <div class="col-md-3"><label>Payout Type</label>
                                                    <select name="payout_type" class="form-control payout-type-select" required>
                                                        <option value="fixed">Fixed %</option>
                                                        <option value="equal_to_achievement">Equal to Achievement %</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2"><label>Payout % (if fixed)</label>
                                                    <input type="number" step="1" name="payout_percent" class="form-control">
                                                </div>
                                                @if (hasPermission('sales_target.sales-salary-brackets.create')) 
                                                    <div class="col-md-3 d-flex align-items-end pt-2">
                                                        <button type="submit" class="btn btn-primary w-100">Add</button>
                                                    </div>
                                                @endif
                                            </div>
                                        </form>

                                    <table class="table table-bordered mt-3">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Achievement Range</th>
                                                <th>Payout Type</th>
                                                <th>Payout %</th>
                                                <th>Active</th> 
                                                <th colspan="2">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            @forelse($brackets as $b)  
                                                <tr> 
                                                    <td>
                                                        <input type="number"   step="1"   name="min_percent"    value="{{ $b->min_percent }}"     class="form-control form-control-sm d-inline"   style="width:300px"   form="update-slab-{{ $b->id }}">
                                                        -
                                                        <input type="number"   step="1000"  name="max_percent" value="{{ $b->max_percent }}" class="form-control form-control-sm d-inline"  style="width:300px" form="update-slab-{{ $b->id }}"  placeholder="∞">%
                                                    </td>

                                                    <td>  
                                                        <select name="payout_type" class="form-control form-control-sm"  form="update-slab-{{ $b->id }}">
                                                            <option value="fixed" {{ $b->payout_type == 'fixed' ? 'selected' : '' }}>Fixed %</option>
                                                            <option value="equal_to_achievement" {{ $b->payout_type == 'equal_to_achievement' ? 'selected' : '' }}>= Achievement %</option>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        @if($b->payout_type == 'fixed')
                                                           <div class="input-group input-group-sm" style="width: 110px;">
                                                                <input type="number"  step="1"   name="payout_percent"  value="{{ $b->payout_percent }}"  class="form-control"  form="update-slab-{{ $b->id }}">
                                                                <span class="input-group-text">%</span>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">Auto</span>
                                                            <input type="hidden" name="payout_percent" value="" form="update-slab-{{ $b->id }}">
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <select name="is_active"   class="form-control form-control-sm"  form="update-slab-{{ $b->id }}">
                                                            <option value="1" {{ $b->is_active == 1 ? 'selected' : '' }}>
                                                                Active
                                                            </option>
                                                            <option value="0" {{ $b->is_active == 0 ? 'selected' : '' }}>
                                                                Inactive
                                                            </option>
                                                        </select>
                                                    </td>

                                                    <td class="text-center">

                                                        <form id="update-slab-{{ $b->id }}" action="{{ route('sales_target.sales-salary-brackets.update', $b->id) }}"  method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            @if (hasPermission('sales_target.sales-salary-brackets.update')) 
                                                                <button type="submit"  class="btn btn-xs btn-outline-warning" onclick="return confirmSubmit(event, this, 'Update');">
                                                                    <i class="far fa-edit"></i>
                                                                </button>
                                                            @endif
                                                        </form>

                                                    </td>
                                                    
                                                    <form action="{{ route('sales_target.sales-salary-brackets.destroy', $b->id) }}" method="POST" class="d-inline">
                                                        <td> 
                                                            @csrf
                                                            @method('DELETE')
                                                            @if (hasPermission('sales_target.sales-salary-brackets.destroy')) 
                                                                <button type="submit" class="btn btn-xs btn-outline-danger " onclick="return confirmSubmit(event, this, 'Delete');"> <i class="far fa-trash-alt"></i></button>
                                                            @endif
                                                        </td>
                                                    </form>
                                                   
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

