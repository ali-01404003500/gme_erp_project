@extends('layout.app')
@section('title', 'Add Offer')
@section('description', 'Add Offer')
@section('page-head')
    <style>
        td.sale-product {
            border-bottom: none !important;
            border-top: none !important;
        }

        .removed {
            display: none;
        }

        .loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
            cursor: progress;
        }

        .loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            border: 4px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: button-loading-spinner 1s ease infinite;
        }

        @keyframes button-loading-spinner {
            from {
                transform: rotate(0turn);
            }

            to {
                transform: rotate(1turn);
            }
        }

        .loading:disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        .offer-section-wrapper {
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
            padding: 15px;
            border-radius: 5px;
            position: relative;
        }

        .remove-offer-section {
            position: absolute;
            top: 10px;
            right: 10px;
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
                                        {{ trans('menu.offer-create-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.offer-create-menu-title') }}</h4>
                            <x-error-alart />
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">

                            <form action="{{ route('inv.offers.store') }}" method="post" onsubmit="return handleSubmit()">
                                @csrf

                                <div class="row">
                                    <label for="">Product Offer Information</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="">Offer Title:</label>
                                            <input type="text" class="form-control" name="title" id=""
                                                value="{{ old('title') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">Applied Date:</label>
                                            <input type="text" class="form-control flatdate" name="applied_date"
                                                placeholder="DD/MM/YYYY" value="{{ old('applied_date') }}" id="">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">Stop Date:</label>
                                            <input type="text" class="form-control flatdate" name="stop_date"
                                                placeholder="DD/MM/YYYY" id="" value="{{ old('stop_date') }}">
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label for="">Times:</label>
                                            <input type="text" class="form-control" name="times" id=""
                                                value="{{ old('times') }}">
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label for="">Select Offer:</label>
                                            <select name="offer_type" id="offer_type" class="form-control">
                                                <option value="">Select Offer Type</option>
                                                <option value="discount" @if (old('offer_type') == 'discount') selected @endif>
                                                    Discounts</option>
                                                <option value="gift" @if (old('offer_type') == 'gift') selected @endif>
                                                    Gifts</option>
                                                <option value="clearance" @if (old('offer_type') == 'clearance') selected @endif>
                                                    Clearance</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label for="">Invoice Type:</label>
                                            <select name="invoice_type" id="" class="form-control">
                                                <option value="paid" @if (old('invoice_type') == 'paid') selected @endif>
                                                    PAID</option>
                                                <option value="paid_due" @if (old('invoice_type') == 'paid_due') selected @endif>
                                                    PAID & DUE</option>
                                                <option value="n/a" @if (old('invoice_type') == 'n/a') selected @endif>N/A
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label for="">Rule Status:</label>
                                            <select name="rule_status" id="" class="form-control">
                                                <option value="running" @if (old('rule_status') == 'running') selected @endif>
                                                    Running</option>
                                                <option value="stop" @if (old('rule_status') == 'stop') selected @endif>
                                                    Stop</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label for="">Rules Applied Type:</label>
                                            <select name="rule_type" id="" class="form-control">
                                                <option value="once_in_time"
                                                    @if (old('rule_type') == 'once_in_time') selected @endif>Once in time</option>
                                                <option value="daily" @if (old('rule_type') == 'daily') selected @endif>
                                                    Daily</option>
                                                <option value="weekly" @if (old('rule_type') == 'weekly') selected @endif>
                                                    Weekly</option>
                                                <option value="monthly" @if (old('rule_type') == 'monthly') selected @endif>
                                                    Monthly</option>
                                                <option value="yearly" @if (old('rule_type') == 'yearly') selected @endif>
                                                    Yearly</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-4">
                                        <h3>Discount Details </h3>
                                    </div>

                                    <!-- Gift Offers Container -->
                                    <div class="col-md-12 offers offers-gift">
                                        <div id="gift-offers-container"></div>
                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="addGiftOffer()">
                                                <i class="fa fa-plus"></i>
                                                Add Gift Offer
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Discount Offers Container -->
                                    <div class="col-md-12 offers offers-discount">
                                        <div id="discount-offers-container"></div>
                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="addDiscountOffer()">
                                                <i class="fa fa-plus"></i>
                                                Add Discount Offer
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Clearance Offer -->
                                    <div class="col-md-12 offers offers-clearance">
                                        <div class="offer-section-wrapper">
                                            <div class="range-section">
                                                <h4>Buying Amount Ranges</h4>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="6">
                                                                    <div class="row add-range-row">
                                                                        <div class="col-md-2">
                                                                            <label>* Start Range</label>
                                                                            <input type="number" class="numberOnly form-control buying_amount_from" placeholder="From">
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label>* End Range</label>
                                                                            <input type="number" class="numberOnly form-control buying_amount_to" placeholder="To">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label>* Discount Type</label>
                                                                            <select class="form-control discount_type">
                                                                                <option value="">Select</option>
                                                                                <option value="flat">Flat</option>
                                                                                <option value="percentage">Percentage</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label>* Discount Amount</label>
                                                                            <input type="number" class="numberOnly form-control discount_amount" placeholder="Amount">
                                                                        </div>
                                                                        <div class="col-md-2 d-flex align-items-end justify-content-end mt-3">
                                                                            <button class="btn btn-primary w-100 btn-xs" type="button" onclick="addRangeWithDiscount(this)">
                                                                                <i class="fa fa-plus"></i> Add
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>SL</th>
                                                                <th>Start Amount</th>
                                                                <th>End Amount</th>
                                                                <th>Discount Type</th>
                                                                <th>Discount Amount</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="range-tbody">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="products-section mt-4">
                                                <h4>Clearance Products</h4>
                                                <div class="table-responsive" style="overflow: visible;">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="3">
                                                                   <div class="row add-product-row">
                                                                        <div class="col-md-4">
                                                                            <label>Brand</label>
                                                                            <select class="form-control brand_id to-select">
                                                                                <option value="">Select</option>
                                                                                @foreach ($brands as $brand)
                                                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Product</label>
                                                                            <select class="form-control product_id to-select">
                                                                                <option value="">Select</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4 d-flex align-items-end mt-3">
                                                                            <button class="btn btn-primary w-100 btn-xs" type="button" onclick="addClearanceProduct(this)">
                                                                                <i class="fa fa-plus"></i> Add
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>SL</th>
                                                                <th>Product</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="products-tbody">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">Save</button>
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
    let cache = {};
    let giftOfferIndex = 0;
    let discountOfferIndex = 0;
    let productCache = {};

    $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
        let cacheKey = options.url;
        if (cache[cacheKey]) {
            jqXHR.abort();
            options.success(cache[cacheKey]);
            return;
        }
        let originalSuccess = options.success;
        options.success = function(data) {
            cache[cacheKey] = data;
            if (originalSuccess) {
                originalSuccess(data);
            }
        };
    });

    function updateOfferstype(offerType) {
        $('.offers').addClass('removed');
        if (offerType === 'gift') {
            $('.offers-gift').removeClass('removed');
        } else if (offerType === 'discount') {
            $('.offers-discount').removeClass('removed');
        } else if (offerType === 'clearance') {
            $('.offers-clearance').removeClass('removed');
        }
    }

    $(document).ready(function() {
        $('.offers').addClass('removed');
        updateOfferstype($('#offer_type').val());
        
        // Add initial offer section based on offer type
        const initialOfferType = $('#offer_type').val();
        if (initialOfferType === 'gift') {
            addGiftOffer();
        } else if (initialOfferType === 'discount') {
            addDiscountOffer();
        }
        
        $('#offer_type').on('change', function() {
            const newOfferType = $(this).val();
            updateOfferstype(newOfferType);
            
            // Add initial offer if containers are empty
            if (newOfferType === 'gift' && $('#gift-offers-container').children().length === 0) {
                addGiftOffer();
            } else if (newOfferType === 'discount' && $('#discount-offers-container').children().length === 0) {
                addDiscountOffer();
            }
        });

        $('.to-select').each(function() {
            new TomSelect(this, {});
        });

        // Load products with brand info
        function loadProducts(brandId, targetSelect) {
            const url = `{{ route('inv.brands.product-catalogs', 'ID') }}`;
            $.ajax({
                url: url.replace('ID', brandId),
                method: 'GET',
                success: function(response) {
                    targetSelect.prop('tomselect')?.clearOptions();
                    let options = '<option value="">Select Product</option>';
                    
                    // Store products with full info
                    productCache[brandId] = response;
                    
                    $.each(response, function(index, product) {
                        options += `<option value="${product.id}" data-brand="${product.brand?.name || ''}" data-model="${product.model || ''}">${product.name}</option>`;
                    });
                    targetSelect.html(options);
                    targetSelect.prop('tomselect')?.sync();
                },
                error: function(xhr) {
                    console.error('Error loading products:', xhr.responseText);
                }
            });
        }

        $(document).on('change', '.brand_id', function() {
            const brandId = $(this).val();
            const closestContainer = $(this).closest('.fields, .add-product-row');
            const productSelect = closestContainer.find('select.product_id');
            
            if(brandId) {
                loadProducts(brandId, productSelect);
            } else {
                productSelect.html('<option value="">Select</option>').prop('tomselect')?.clear();
            }
        });

        $(document).on('click', '.remove-product', function() {
            $(this).closest('tr').remove();
        });

        $(document).on('click', '.remove-offer-section', function() {
            $(this).closest('.offer-section-wrapper').remove();
        });
    });

    // Gift Offer Template
    function addGiftOffer() {
        const template = `
            <div class="offer-section-wrapper" data-index="${giftOfferIndex}">
                <button type="button" class="btn btn-danger btn-sm remove-offer-section">
                    <i class="fa fa-times"></i> Remove
                </button>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width:50%; text-align:center;">Buying Product</th>
                                <th style="width:50%; text-align:center;">Offers Product</th>
                            </tr>
                            <tr>
                                <th>
                                    <div class="fields">
                                        <label>Brand</label>
                                        <select class="form-control brand_id to-select">
                                            <option value="">Select</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                        <label>Product</label>
                                        <select class="form-control product_id to-select">
                                            <option value="">Select</option>
                                        </select>
                                        <label>Quantity</label>
                                        <input type="number" class="numberOnly form-control quantity" min="1">
                                        <label>&nbsp;</label>
                                        <button class="btn btn-primary w-100 btn-xs" type="button" onclick="addProductToBuying(this)">
                                            <i class="fa fa-plus"></i> Add
                                        </button>
                                    </div>
                                </th>
                                <th>
                                    <div class="fields align-top">
                                        <label>Brand</label>
                                        <select class="form-control brand_id to-select">
                                            <option value="">Select</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                        <label>Product</label>
                                        <select class="form-control product_id to-select">
                                            <option value="">Select</option>
                                        </select>
                                        <label>Quantity</label>
                                        <input type="number" class="numberOnly form-control quantity" min="1">
                                        <label>&nbsp;</label>
                                        <button class="btn btn-primary w-100 btn-xs" type="button" onclick="addProductToOffer(this)">
                                            <i class="fa fa-plus"></i> Add
                                        </button>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="background-color: #baffdd;text-align:center;font-weight: 800;">Buying Products</td>
                                <td style="background-color: #baffdd;text-align:center;font-weight: 800;">Offers Products</td>
                            </tr>
                            <tr>
                                <td class="buying align-top p-0">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </td>
                                <td class="offer p-0 align-top">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        `;
        
        $('#gift-offers-container').append(template);
        
        // Initialize TomSelect for new selects
        $(`[data-index="${giftOfferIndex}"] .to-select`).each(function() {
            new TomSelect(this, {});
        });
        
        giftOfferIndex++;
    }

    // Discount Offer Template
    function addDiscountOffer() {
        const template = `
            <div class="offer-section-wrapper" data-index="${discountOfferIndex}">
                <button type="button" class="btn btn-danger btn-sm remove-offer-section">
                    <i class="fa fa-times"></i> Remove
                </button>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width:50%; text-align:center;">Buying Product</th>
                                <th style="width:50%; text-align:center;">Discount Price</th>
                            </tr>
                            <tr>
                                <th>
                                    <div class="fields">
                                        <label>Brand</label>
                                        <select class="form-control brand_id to-select">
                                            <option value="">Select</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                        <label>Product</label>
                                        <select class="form-control product_id to-select">
                                            <option value="">Select</option>
                                        </select>
                                        <label>Quantity</label>
                                        <input type="number" class="numberOnly form-control quantity" min="1">
                                        <label>&nbsp;</label>
                                        <button class="btn btn-primary w-100 btn-xs" type="button" onclick="addProductToBuying(this)">
                                            <i class="fa fa-plus"></i> Add
                                        </button>
                                    </div>
                                </th>
                                <th>
                                    <div class="fields align-top">
                                        <label>Discount Type</label>
                                        <select class="form-control discount_type to-select">
                                            <option value="">Select</option>
                                            <option value="flat_discount">Flat Discount</option>
                                            <option value="percentage_discount">Percentage Discount</option>
                                        </select>
                                        <label>Discount Amount</label>
                                        <input type="number" class="numberOnly form-control discount_amount" min="0">
                                        <label>&nbsp;</label>
                                        <button class="btn btn-primary w-100 btn-xs" type="button" onclick="addDiscountToOffer(this)">
                                            <i class="fa fa-plus"></i> Add
                                        </button>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="background-color: #baffdd;text-align:center;font-weight: 800;">Buying Products</td>
                                <td style="background-color: #baffdd;text-align:center;font-weight: 800;">Discounts</td>
                            </tr>
                            <tr>
                                <td class="buying align-top p-0">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </td>
                                <td class="offer p-0 align-top">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Discount Type</th>
                                                <th>Amount</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        `;
        
        $('#discount-offers-container').append(template);
        
        // Initialize TomSelect for new selects
        $(`[data-index="${discountOfferIndex}"] .to-select`).each(function() {
            new TomSelect(this, {});
        });
        
        discountOfferIndex++;
    }

    function addProductToBuying(button) {
        addProduct(button, 'buying');
    }

    function addProductToOffer(button) {
        addProduct(button, 'offer');
    }

    function addProduct(button, productType) {
        const wrapper = $(button).closest('.offer-section-wrapper');
        const index = wrapper.attr('data-index');
        const container = $(button).closest('table').find(`.${productType}`);
        const brandId = $(button).closest('.fields').find('.brand_id').val();
        const brandName = $(button).closest('.fields').find('.brand_id option:selected').text();
        const productId = $(button).closest('.fields').find('.product_id').val();
        const productSelect = $(button).closest('.fields').find('.product_id option:selected');
        const productName = productSelect.text();
        const productModel = productSelect.attr('data-model') || '';
        const quantity = $(button).closest('.fields').find('.quantity').val();

        if (!brandId || !quantity) {
            toastr.warning('Please select brand and quantity');
            return;
        }

        if (!productId || productId == '') {
            $(button).addClass('loading');
            const url = `{{ route('inv.brands.product-catalogs', 'ID') }}`;

            $.ajax({
                url: url.replace('ID', brandId),
                type: 'GET',
                success: function(products) {
                    $.each(products, function(_, product) {
                        const fullProductName = `${brandName} - ${product.name}${product.model ? ' (' + product.model + ')' : ''}`;
                        const newRow = createProductRow(productType, fullProductName, product, index, quantity);
                        container.find('tbody').append(newRow);
                    });
                    $(button).removeClass('loading');
                },
                error: function(xhr) {
                    console.error('Error loading products:', xhr.responseText);
                    $(button).removeClass('loading');
                    toastr.error('Failed to load products');
                }
            });
        } else {
            const fullProductName = `${brandName} - ${productName}${productModel ? ' (' + productModel + ')' : ''}`;
            const newRow = createProductRow(productType, fullProductName, {
                id: productId,
                name: productName
            }, index, quantity);
            container.find('tbody').append(newRow);
        }

        // Reset form fields
        $(button).closest('.fields').find('.brand_id, .product_id').val('');
        $(button).closest('.fields').find('.quantity').val('');
        $(button).closest('.fields').find('select.to-select').each(function() {
            this.tomselect?.clear();
        });
    }

    function createProductRow(productType, displayName, product, index, quantity) {
        return `
            <tr>
                <td>
                    ${displayName}
                    <input type="hidden" name="${productType}_product_id[${index}][]" value="${product.id}">
                </td>
                <td>
                    ${quantity}
                    <input type="hidden" name="${productType}_quantity[${index}][]" value="${quantity}">
                </td>
                <td>
                    <button class="btn btn-danger btn-xs remove-product" type="button">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    function addDiscountToOffer(elem) {
        const wrapper = $(elem).closest('.offer-section-wrapper');
        const index = wrapper.attr('data-index');
        const container = $(elem).closest('table').find('.offer');
        const discountType = $(elem).closest('.fields').find('.discount_type').val();
        const discountAmount = $(elem).closest('.fields').find('.discount_amount').val();
        
        if (!discountType || !discountAmount) {
            toastr.warning('Please enter a discount type and amount.');
            return;
        }

        const discountRow = createDiscountRow(discountType, discountAmount, index);
        container.find('tbody').append(discountRow);

        $(elem).closest('.fields').find('.discount_type, .discount_amount').val('');
        $(elem).closest('.fields').find('select.to-select').each(function() {
            this.tomselect?.clear();
        });
    }

    function createDiscountRow(discountType, discountAmount, index) {
        const typeLabel = discountType == 'flat_discount' ? 'Flat Discount' : 'Percentage Discount';
        return `
            <tr>
                <td>
                    ${typeLabel}
                    <input type="hidden" name="discount_type[${index}][]" value="${discountType}">
                </td>
                <td>
                    ${discountAmount}
                    <input type="hidden" name="discount_amount[${index}][]" value="${discountAmount}">
                </td>
                <td>
                    <button class="btn btn-danger btn-xs remove-product" type="button">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    function addRangeWithDiscount(button) {
        const form = $(button).closest('.add-range-row');
        const fromValue = form.find('.buying_amount_from').val();
        const toValue = form.find('.buying_amount_to').val();
        const discountType = form.find('.discount_type').val();
        const discountAmount = form.find('.discount_amount').val();

        if (!fromValue || !toValue || !discountType || !discountAmount || parseFloat(fromValue) >= parseFloat(toValue)) {
            toastr.warning('Please enter valid range and discount details.');
            return;
        }

        const tbody = $(button).closest('.range-section').find('.range-tbody');
        const sl = tbody.find('tr').length + 1;
        const typeLabel = discountType === 'flat' ? 'Flat' : 'Percentage';

        const rowTemplate = `
            <tr>
                <td>${sl}</td>
                <td>
                    ${fromValue}
                    <input type="hidden" name="buying_amount_from[0][]" value="${fromValue}">
                </td>
                <td>
                    ${toValue}
                    <input type="hidden" name="buying_amount_to[0][]" value="${toValue}">
                </td>
                <td>
                    ${typeLabel}
                    <input type="hidden" name="gift_type[0][]" value="${discountType}">
                </td>
                <td>
                    ${discountAmount}
                    <input type="hidden" name="gift_amount[0][]" value="${discountAmount}">
                </td>
                <td>
                    <button class="btn btn-danger btn-xs remove-product" type="button">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        `;

        tbody.append(rowTemplate);

        // Reset form
        form.find('input').val('');
        form.find('select').val('');
    }

    function addClearanceProduct(button) {
        const form = $(button).closest('.add-product-row');
        const brandSelect = form.find('.brand_id');
        const brandId = brandSelect.val();
        const brandName = brandSelect.find('option:selected').text();
        const productSelect = form.find('.product_id');
        const productId = productSelect.val();
        const productName = productSelect.find('option:selected').text();
        const productModel = productSelect.find('option:selected').attr('data-model') || '';

        if (!brandId || !productId) {
            toastr.warning('Please select brand and product');
            return;
        }

        const tbody = $(button).closest('.products-section').find('.products-tbody');
        const sl = tbody.find('tr').length + 1;
        
        const displayName = `${brandName} - ${productName}${productModel ? ' (' + productModel + ')' : ''}`;

        const rowTemplate = `
            <tr>
                <td>${sl}</td>
                <td>
                    ${displayName}
                    <input type="hidden" name="clearance_product_id[0][]" value="${productId}">
                </td>
                <td>
                    <button class="btn btn-danger btn-xs remove-product" type="button">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        `;

        tbody.append(rowTemplate);

        // Reset form
        brandSelect.val('').prop('tomselect')?.clear();
        productSelect.val('').prop('tomselect')?.clear();
    }
</script>
@endsection