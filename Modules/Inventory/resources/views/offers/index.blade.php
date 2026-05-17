
@extends('layout.app')
@section('title', 'Offers List')
@section('description', 'Offers List')
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
                                        {{ trans('menu.offer-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('inv.offers.create'))
                                    <a class="btn btn-xs btn-primary btn-sm" href="{{route("inv.offers.create")}}">
                                        Add New
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

            {{-- <div class="card mb-4">
                <div class="card-body">
                    <div class="support-form d-flex justify-content-between align-items-center flex-wrap">
                        <form action="{{ route('inv.product-types.store') }}" method="post">
                            <div class="row">
                                <div class="col">
                                    <label for="serial" class="text-muted" style="font-size: 0.8rem;">Serial:</label>
                                    <input id="serial" name="serial" type="number" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="productName" class="text-muted" style="font-size: 0.8rem;">Product Name:</label>
                                    <input id="productName" name="productName" type="text" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="productModel" class="text-muted" style="font-size: 0.8rem;">Product Model:</label>
                                    <select id="productModel" name="productModel" class="form-control tom-select">
                                        <option value="01">Model 1</option>
                                        <option value="02">Model 2</option>
                                        <option value="03">Model 3</option>
                                        <option value="04">Model 4</option>
                                        <option value="05">Model 5</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="quantity" class="text-muted" style="font-size: 0.8rem;">Quantity:</label>
                                    <input id="quantity" name="quantity" type="number" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="salesQuantity" class="text-muted" style="font-size: 0.8rem;">Sales Quantity:</label>
                                    <input id="salesQuantity" name="salesQuantity" type="number" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="purchaseQuantity" class="text-muted" style="font-size: 0.8rem;">Purchase Quantity:</label>
                                    <input id="purchaseQuantity" name="purchaseQuantity" type="number" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="ruleStatus" class="text-muted" style="font-size: 0.8rem;">Rule Status:</label>
                                    <select id="ruleStatus" name="ruleStatus" class="tom-select form-control">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="stopDate" class="text-muted" style="font-size: 0.8rem;">Stop Date:</label>
                                    <input id="stopDate" name="stopDate" type="date" class="form-control">
                                </div>
                            </div>
                        </form>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-16">
                                @if (hasPermission('inv.products.create'))
                                    <a class="btn btn-xs btn-primary me-1" href="{{route("inv.products.create")}}">
                                        Search by
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.offer-list-menu-title') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td class="text-center">
                                                    <input type="text" class="form-control" placeholder="Search" name="title" value="{{ request('title') }}">
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
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $offers])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 8%">Sl</th>
                                        <th class="text-center">Title</th>
                                        <th class="text-center">Start Date</th>
                                        <th class="text-center">End Date</th>
                                        <th class="text-center">Offer Type</th>
                                        {{-- <th class="text-center">Product</th> --}}
                                        {{-- <th class="text-center">Model</th> --}}
                                        {{-- <th class="text-center">Serial</th> --}}
                                        {{-- <th class="text-center">Quantity</th> --}}
                                        {{-- <th class="text-center">Sales Quantity</th> --}}
                                        {{-- <th class="text-center">Purchase Quantity</th> --}}
                                        {{-- <th class="text-center">Rule Status</th> --}}
                                        {{-- <th class="text-center">Brand</th>
                                        <th class="text-center">Type</th> --}}
                                        <th class="text-center no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @csrf
                                    @foreach ($offers as $key => $offer)
                                        <tr>
                                        <td class="text-center">{{ ($offers->currentPage() - 1) * $offers->perPage() + $loop->iteration  }}</td>
                                            {{-- <td class="text-center">{{ $offer->title }}</td> --}}
                                            <td class="text-left">
                                                <a href="{{ route('inv.offers.show', $offer->id) }}">{{ $offer->title }}</a>
                                            </td>
                                            <td class="text-center">{{ $offer->applied_date }}</td>
                                            <td class="text-center">{{ $offer->stop_date }}</td>
                                            <td class="text-center">{{ ucfirst($offer->offer_type) }}</td>
                                            {{-- <td class="text-center">{{ $offer->brand->name }}</td>
                                            <td class="text-center">{{ $offer->productType->name }}</td> --}}
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                    @if (hasPermission('inv.offers.update'))
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('inv.offers.edit', $offer->id) }}"><i
                                                                class="far fa-edit"></i></a>
                                                    @endif

                                                    @if(hasPermission('inv.offers.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('inv.offers.destroy', $offer->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    @endif

                                                    @if (hasPermission('inv.offers.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('inv.offers.show', $offer->id) }}"><i class="fas fa-eye"></i>
                                                        </a>
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
                                <h5 class="modal-title">Add Product Type</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <form action="{{ route('inv.product-types.store') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Code</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="code" class="form-control" placeholder=" Code *"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="name" class="form-control" placeholder=" Name *"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="inputError" class="col-sm-3 control-label bolder">
                                            Status</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <div class="radio">
                                                <label>
                                                    <input name="status" type="radio" value="1" class="ace"
                                                        checked>
                                                    <span class="lbl"> Active</span>
                                                </label>
                                                <label>
                                                    <input name="status" type="radio" value="0" class="ace">
                                                    <span class="lbl"> In active</span>
                                                </label>
                                            </div>

                                            @error('status')
                                                <span class="text-danger">
                                                    {{ message }}
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
                    <h5 class="modal-title">Edit </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="editFrom">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label for="code" class="col-sm-12 col-form-label">Code</label>
                            <div class="col-sm-12">
                                <input name="code" id="code" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="name" class="col-sm-12 col-form-label">Name</label>
                            <div class="col-sm-12">
                                <input name="name" id="name" class="form-control" type="text">
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
    </div>
@endsection
<!-- CONTENT AREA -->
@section('page_scripts')

    <script>
        // $(document).ready(function(e) {
        //     $(document).on('click', '.btn-edit', function() {
        //         console.log($(this).data('name'));
        //         $('#name').val($(this).data('name'));
        //         $('#code').val($(this).data('code'));
        //         $("#editFrom").attr("action", $(this).data('action'));
        //     });
        // });

        
    </script>
@endsection
