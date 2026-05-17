@extends('layout.app')
@section('title', 'Add Product Transfer Request')
@section('description', 'Add Product Transfer Request')
@section('page-head')
    <style>
        .ts-control{
            flex-wrap: nowrap !important;
        }
    </style>
@endsection
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.product-transfer-request-create-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('inv.product-transfer-requests.index'))
                            <a href="{{ route('inv.product-transfer-requests.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">
                        {{ trans('menu.product-transfer-request-create-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('inv.product-transfer-requests.store') }}" method="POST" id="product_transfer_form"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <h3>Transfer Details</h3>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="request_date">Request Date</label>
                                            <input type="date" name="request_date" class="form-control flatdate" id="request_date"
                                                placeholder="Request Date" value="{{ old('request_date', date('Y-m-d')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="source_branch_id">Source Branch*</label>
                                            <select name="source_branch_id" class="form-control tom-select source_branch"
                                                id="source_branch_id">
                                                <option value="">Select</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}"
                                                    {{ old('source_branch_id') == $branch->id ? 'selected' : '' }}>
                                                        {{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="destination_branch_id">Destination Branch*</label>
                                            <select name="destination_branch_id" class="form-control tom-select"
                                                id="destination_branch_id" @if(auth()->user()->branch_id != 1) disabled @endif>
                                                <option value="">Select</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}"
                                                    {{ old('destination_branch_id', auth()->user()->branch_id) == $branch->id ? 'selected' : '' }}>
                                                        {{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                            @if(auth()->user()->branch_id != 1)
                                            <input type="hidden" name="destination_branch_id" value="{{auth()->user()->branch_id}}">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="remarks">Remarks</label>
                                            <textarea name="remarks" id="remarks" cols="30" rows="2" class="form-control">{{ old('remarks') }}</textarea>
                                        </div>
                                    </div>



                                    <div class="col-md-12">
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h3>Product Information</h3>
                                                <table class="table table-bordered" id="product_info_table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 25%">Product Name</th> 
                                                            <th style="width: 15%">Available Stock</th>
                                                            <th style="width: 15%">Quantity</th>
                                                            <th style="width: 5%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $old = old();
                                                            $product_catalog_ids = $old['product_catalog_id'] ?? [];

                                                            $quantities = $old['quantity'] ?? [];
                                                        @endphp
                                                        @if ($product_catalog_ids)
                                                            @for ($i = 0; $i < count($product_catalog_ids); $i++)
                                                                <tr>
                                                                    <td>
                                                                        <select name="product_catalog_id[]" class="form-control  select-product">
                                                                            <option value="">Choose Product</option> 
                                                                            <option value="{{ $product_catalog_ids[$i] }}" selected >
                                                                                {{ $product_catalog_ids[$i]->product->name }}
                                                                            </option>
                                                                          
                                                                        </select>
                                                                    </td> 
                                                                    <td class="text-center avoilable-stock"></td>
                                                                    <td>
                                                                        <input type="number" name="quantity[]" value="{{ $quantities[$i] }}"
                                                                            id="quantity_{{ $i + 1 }}"
                                                                            class="form-control quantities" placeholder="Quantity">
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-danger btn-xs" onclick="removeRow(this)"><i
                                                                                class="fa fa-times"></i></button>
                                                                    </td>
                                                                </tr>
                                                            @endfor
                                                        @else
                                                            <tr>
                                                                <td>
                                                                    <select name="product_catalog_id[]"
                                                                            class="form-control  select-product">
                                                                        <option value="">Choose Product</option> 
                                                                    </select>
                                                                </td> 
                                                                <td class="text-center avoilable-stock">

                                                                </td>
                                                                <td>
                                                                    <input type="number" name="quantity[]"
                                                                        class="form-control quantities"
                                                                        placeholder="Quantity">
                                                                </td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-xs"
                                                                        id="remove_row"
                                                                        onclick="removeRow(this)">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="3" style="text-align: right;">
                                                                <div class="d-flex justify-content-end">
                                                                    <button type="button" class="btn btn-info btn-sm"
                                                                        id="add_row">
                                                                        <i class="fa fa-plus"></i> Add</button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div
                                            class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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


            const productSelect = new TomSelect(".select-product", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('sales.sales-orders-autocomplete.products') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {
                            productSelect.clearOptions();
                            callback(res.map(item => ({ id: item.label, text: item.label })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 
 

            $('#approve').click(function() {
                $("#status").val("approved");
                return true;
            });
            $('#approve').click(function() {
                $("#status").val("approved");
                return true;
            });
            
            $(".select-product").trigger('change', ['init']);

            $('#product_transfer_form').on('submit', function(e) {
                let isValid = true;
                let hasError = false;

                $('#product_info_table tbody tr').each(function() {
                    const availableStockText = $(this).find('.avoilable-stock').text();
                    const availableStock = parseFloat(availableStockText) || 0;
                    const quantityVal = $(this).find('.quantities').val();
                    const quantity = parseFloat(quantityVal) || 0;

                    if (quantity > availableStock) {
                        isValid = false;
                        $(this).find('.quantities').css('border', '1px solid red');
                        if (!hasError) {
                            toastr.error("Quantity can't be greater than available stock");
                            hasError = true;
                        }
                    } else {
                        $(this).find('.quantities').css('border', '');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>

    <script type="text/javascript">
        const row = $("#product_info_table tbody tr:first-child").clone();
        row.find('input').val('');
        row.find('select option:selected').removeAttr('selected');

        $("#add_row").click(function() {
            const newRow = row.clone(); 
            $("#product_info_table tbody").append(newRow);
            prouctAutocompleteLoad(newRow);
        });
        

        function removeRow(row) {
            
            if($(row).closest("tbody").find("tr").length > 1){
                $(row).closest('tr').remove();
            }else{
                $(row).closest('tr').find('.select-product')[0].tomselect?.clear();
                $(row).closest('tr').find('.quantities').val('');

            }
        }

        $(document).on('input', '.quantities', function() {
            const availableStockText = $(this).closest('tr').find('.avoilable-stock').text();
            const availableStock = parseFloat(availableStockText) || 0;
            const quantity = parseFloat($(this).val()) || 0;
            
            if (quantity > availableStock) {
                toastr.error("Quantity can't be greater than available stock");
                $(this).val(availableStock);
            }
        })

        $(document).on('change', '.select-product', function(e, fromInit) {
            const productId = $(this).val();
            const sourceBranch = $('select.source_branch').val();
            const quantity = $(this).closest('tr').find('input[name="quantity[]"]');
     
            
            const selectedOption = $(".select-product option:selected[value='" + productId + "']");
            if( selectedOption.length > 1 ) {
               
                this.tomselect?.clear();
                quantity.val('');
                toastr.error("The selected product is already added");
                return;
            }
            
            //load available stock
            if( productId == "" ) { 
                $(this).closest('tr').find('.avoilable-stock').text('');
                return;
            } 
             
            if( sourceBranch == "" ) {
                toastr.warning("Please select source branch");
                return
            };
             $.get("{{ route('inv.stocks.product-available-in-branch') }}?product_id=" + productId + "&branch_id=" + sourceBranch).then(function(data) {
                //  console.log(data);
                 $(quantity).closest('tr').find('.avoilable-stock').text(data);
                if(!fromInit) {
                     quantity.val(1);
                }
             })

             
        });

        function prouctAutocompleteLoad(row){

            const p = $(row).find(".select-product");

            if (!p.length) return;

            if (p[0].tomselect) {
                p[0].tomselect.destroy();
            }

            new TomSelect(p[0], {
                valueField: "id",
                labelField: "text",
                searchField: ["text"],

                load: function(search, callback) {

                    if (!search.length || search.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('sales.sales-orders-autocomplete.products') }}",
                        type: "GET",
                        data: { search: search },

                        success: function(res) {
                            callback(res.map(item => ({
                                id: item.id,
                                text: item.label
                            })));
                        },

                        error: function() {
                            callback();
                        }
                    });
                }
            });
        }
    </script>

@endsection
