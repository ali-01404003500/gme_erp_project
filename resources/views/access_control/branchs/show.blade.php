@section('title', 'Branch List')
@section('description', 'Branch List')
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
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.branch-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('access_control.branchs.index'))
                                    <a href="{{ route('access_control.branchs.index') }}"
                                        class="btn btn-sm btn-primary btn-add"><i class="las la-plus"></i>
                                        {{ trans('menu.branch-menu-title') }}</a>
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
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.branch-list-menu-title') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Branch Details - {{ $branch->name }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-3">
                                    <h2>{{ $branch->name }}</h2>
                                    <img src="{{ $branch->image }}" width="100%" alt="{{ $branch->name }}" style="border: 2px solid #d1d1d1; border-radius: 5px">
                                    <div style="padding: 20px;">
                                        <h6>Description</h6>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis accusamus earum iusto eaque quibusdam vel iste similique?</p>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <div class="card-body p-0">
                                        <div class="ap-product">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    </thead>
                                                    <tbody>
                                                        {{-- <tr>
                                                            <th>Name</th>
                                                            <td>{{ $branch->name }}</td>
                                                        </tr> --}}
                                                        <tr>
                                                            <th>Branch Type</th>
                                                            <td>{{ $branch->branchType->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Name</th>
                                                            <td>{{ $branch->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Title</th>
                                                            <td>{{ $branch->title }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Division</th>
                                                            <td>{{ optional(App\Models\GeoLocation::find($branch->division))->name??$branch->division }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>District</th>
                                                            <td>{{  optional(App\Models\GeoLocation::find($branch->district))->name??$branch->district }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Police Station</th>
                                                            <td>{{ $branch->police_station }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Contact No</th>
                                                            <td>{{ $branch->contact_no }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Size</th>
                                                            <td>{{ $branch->size }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                </div>
                                <div class="col-md-12 my-4">
                                    <h4>Contact Persons</h4>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Mobile</th>
                                                <th>Designation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($branch->contactPersionDetails as $person)
                                                <tr>
                                                    <td>{{ $person->name }}</td>
                                                    <td>{{ $person->mobile }}</td>
                                                    <td>{{ $person->designation }}</td> 
                                                </tr>
                                            @endforeach
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

    <!-- Edit Modal -->

@endsection
<!-- CONTENT AREA -->
@section('page_scripts')
    <script>
    </script>
@endsection


