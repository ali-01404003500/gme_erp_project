@extends('layout.app')
@section('title',"Product Price List")
@section('description',"Product Price List")

@section('page-head')
<style>
    #confirm_pdf table{
        pointer-events: none;
    }
</style>
@endsection

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
                                        {{ trans('menu.product-price-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                {{-- @if (hasPermission('inv.settings.units.create'))
                                    <button class="btn btn-xs btn-primary me-1" data-bs-toggle="modal"
                                        data-bs-target="#createModal">
                                        Add New
                                    </button>
                                @endif --}}
                                <button id="exportCheckedRows" class="btn btn-primary btn-sm d-inline-block mr-2">
                                    Export Checked Rows to PDF
                                </button>
                                <!-- <a href="{{ request()->url() . '/export' }}" target="_blank"
                                    class="btn btn-success btn-sm d-inline-block">
                                    <i class="las la-file-excel fs-16"></i> Excel
                                </a>  -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            {{-- <td class="text-center">
                                                <select name="name" id="name" class="form-control tom-select"
                                                    data-placeholder="Select Product Name">
                                                    <option value=""></option>
                                                    @foreach ($productCatalogs as $productCatalog)
                                                        <option
                                                            {{ request('name') == $productCatalog->name ? 'selected' : '' }}
                                                            value="{{ $productCatalog->name }}">
                                                            {{ $productCatalog->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="text-center">
                                                <select name="model" id="model" class="form-control tom-select"
                                                    data-placeholder="Select Model Name">
                                                    <option value=""></option>
                                                    @foreach ($productCatalogs as $productCatalog)
                                                        <option
                                                            {{ request('model') == $productCatalog->model ? 'selected' : '' }}
                                                            value="{{ $productCatalog->model }}">
                                                            {{ $productCatalog->model }}</option>
                                                    @endforeach
                                                </select>
                                            </td> --}}

                                            <td class="text-center" width="45%">
                                                <select name="product_brand_ids[]" id="brand"
                                                    class="form-control multi-select" multiple
                                                    data-placeholder="Select Brand Name">
                                                    @foreach ($productBrands as $productBrand)
                                                        <option
                                                            {{ collect(request('product_brand_ids'))->contains($productBrand->id) ? 'selected' : '' }}
                                                            value="{{ $productBrand->id }}">
                                                            {{ $productBrand->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="text-center" width="45%">
                                                <select name="tags[]" id="tags" class="form-control multi-select"
                                                    multiple data-placeholder="Select Tags">
                                                    @foreach ($tags as $tag)
                                                        <option
                                                            {{ collect(request('tags'))->contains($tag->id) ? 'selected' : '' }}
                                                            value="{{ $tag->id }}">
                                                            {{ $tag->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            {{-- <td class="text-center">
                                                <input type="text" class="form-control" name="present_address"
                                                    value="{{ request('present_address') }}" autocomplete="off"
                                                    placeholder="Search by Address">
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control" name="personal_mobile"
                                                    value="{{ request('personal_mobile') }}" autocomplete="off"
                                                    placeholder="Search by Mobile">
                                            </td> --}}
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
            </div>


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.product-price-list-menu-title') }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-between pb-2">
                                    <h3>
                                        Price List
                                        <span class="text-muted  fs-12">({{ $products->count() }})</span>
                                    </h3>

                                    <div>
                                        <div class="input-group mb-3">
                                            <input type="number" max="100" min="0" name="discount" class="form-control" style="width: 128px"  placeholder="Discount (%)" aria-label="Discount (%)" aria-describedby="button-addon2">
                                            <button class="btn btn-outline-secondary" type="button" id="button-addon2" onclick="updateDiscount(this)">Update</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 table-responsive" id="productTable">
                                    <p>
                                        <strong>Date:</strong> {{ now()->format('y-m-d h:i:s') }}
                                    </p>
                                    <p>
                                        This price list validity for 10 Days after submitted this price.
                                    </p>
                                    <table class="table table-bordered"  style="width:100%;" data-pag="" >
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 5%">
                                                    <input type="checkbox" id="checkAll" checked>
                                                </th>
                                                <th class="text-center" style="width: 3%">Sl</th>
                                                <th>Product Name</th>
                                                <th>Brand</th>
                                                <th>Model</th>
                                                <th>Origin</th>
                                                <th>Tag</th>
                                                <th>QTY</th>
                                                <th>MRP</th>
                                                
                                                <th>Offer Price</th>
                                                <th> New MRP</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $key => $product)
                                                <tr>
                                                    <td class="text-center">
                                                        <input type="checkbox" class="product-checkbox" checked value="1">
                                                    </td>
                                                    <td class="text-center">{{ $key + 1 }}</td>
                                                    <td>{{ $product->name }}</td>
                                                    <td>{{ optional($product->brand)->name }}</td>
                                                    <td>{{ optional($product)->model }}</td>
                                                    <td>{{ $product->product_origin }}</td>
                                                    <td>{{ optional($product)->tag->name }}</td>
                                                    <td>1</td>
                                                    <td>{{ $product->mrp }}</td>
                                                    <td class="offer-price" data-original-price="{{ $product->mrp }}">{{ $product->mrp }}</td>
                                                    <td >
                                                        <div class="input-group mb-3" style="flex-wrap: nowrap;">
                                                            <input type="number" value="{{ $product->mrp }}" name="new_mrp" class="form-control" style="width: 128px" placeholder="New MRP" aria-label="New MRP" aria-describedby="button-addon2">
                                                            <button class="btn btn-outline-secondary" type="button" id="button-addon2" onclick="updateNewMrp(this, {{ $product->id }})">Update</button>
                                                        </div>
                                                    </td>
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



@endsection

@section('page_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    document.getElementById('checkAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.product-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    function updateDiscount(button){
        const discount = parseFloat(button.previousElementSibling.value) || 0;
        const offerPrices = document.querySelectorAll('#productTable tbody .offer-price');
        
        // Optimize loop to avoid layout thrashing
        for (let i = 0; i < offerPrices.length; i++) {
            const offerPrice = offerPrices[i];
            // Use dataset to get original price (avoids logic error of double applying discount)
            const originalPrice = parseFloat(offerPrice.dataset.originalPrice);
            
            if (!isNaN(originalPrice)) {
                const discountedPrice = originalPrice - (originalPrice * (discount / 100));
                // Use textContent instead of innerText for better performance (no reflow trigger)
                offerPrice.textContent = discountedPrice.toFixed();
            }
        }
        toastr.success('Discount updated successfully');
    }

    function updateNewMrp(button, productId){

        const url = "{{ route('inv.products.update-catalog-mrp', ':id') }}".replace(':id', productId);
        const newMrp = button.previousElementSibling.value;
        // const offerPrices = document.querySelectorAll('#productTable tbody .offer-price');

        $.post(url, {
            _token: '{{ csrf_token() }}',
            mrp: newMrp,
        }).then(function(response) {

            console.log(response);
            if (response.mrp) {
                toastr.success('MRP updated successfully');
                // Update the MRP in the table
                const offerPriceCell = button.closest('tr').querySelector('.offer-price');
                offerPriceCell.innerText = response.mrp;
                // Update the input field value

                offerPriceCell.previousElementSibling.innerText = response.mrp;
            } else {
                toastr.error('Failed to update MRP');
            }
        }).fail(function() {
            toastr.error('An error occurred while updating MRP');
        });
     
        // toastr.success('MRP updated to ' + newMrp + ' successfully');
    }
</script>

<script>
    $(document).ready(function () {


        initCustomPdf('exportCheckedRows', 'productTable',  {
            title: 'Product Price List',
            // subtitle: 'All Product Price List',
            rowFilter: function (row) {
                console.log(row, row.querySelector('.product-checkbox').checked);
                // Check if the checkbox in the row is checked
                return row.querySelector('.product-checkbox').checked;
            },
            excludeColumns: [0,8,10],
            exportExcel: true
        });

        $(".multi-select").each(function () {
            new TomSelect(this, {
                    plugins: ['remove_button'],
            });
        });
       

        $(document).on('click', '.show-product', function () {
            var ledgerUrl = $(this).data('url');
            $('#product-ledger-modal').find('.modal-body').loadWithSpinner(ledgerUrl);
        });
    });
</script>


@endsection