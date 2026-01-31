<div class="col-md-12 offers offers-discount">
    <div class="table-responsive discount-container">
        <table class="table table-bordered" style="max-width: 100%;">
            <thead>
                <tr>
                    <th style="width:50%; text-align:center;">Buying Product</th>
                    <th style="width:50%; text-align:center;">Discount Price</th>
                </tr>
                <tr>
                    <th>
                        <div class="fields">
                            <label for="brand_id">Brand</label>
                            <select class="form-control brand_id to-select">
                                <option value="">Select</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>

                            <label for="product_id">Product</label>
                            <select class="form-control product_id to-select">
                                <option value="">Select</option>
                            </select>

                            <label for="quantity">Quantity</label>
                            <input type="text" class="numberOnly form-control quantity">

                            <label for="">&nbsp;</label>
                            <button class="btn btn-primary w-100 btn-xs" type="button" onclick="addProductToBuying(this)">
                                <i class="fa fa-plus"></i> Add
                            </button>
                        </div>
                    </th>
                    <th>
                        <div class="fields align-top">
                            <label for="discount_type">Discount Type</label>
                            <select class="form-control discount_type to-select">
                                <option value="">Select</option>
                                <option value="flat_discount">Flat Discount</option>
                                <option value="percentage_discount">Percentage Discount</option>
                            </select>

                            <label for="discount_amount">Discount Amount</label>
                            <input type="text" class="numberOnly form-control discount_amount">

                            <label for="">&nbsp;</label>
                            <button class="btn btn-primary w-100 btn-xs" type="button" onclick="addDiscountToOffer(this)">
                                <i class="fa fa-plus"></i> Add
                            </button>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="background-color: #baffdd;text-align:center;font-weight: 800;">
                        Buying Products
                    </td>
                    <td style="background-color: #baffdd;text-align:center;font-weight: 800;">
                        Discounts
                    </td>
                </tr>
                <tr>
                    <td class="buying align-top p-0">
                        <div class="d-flex">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($offer->offerDetails as $detail)
                                        @foreach($detail->discountSalesProducts as $product)
                                            <tr>
                                                <td>
                                                    {{ $product->product->name }}
                                                    <input type="hidden" name="buying_product_id[{{ $loop->parent->index }}][]" value="{{ $product->sales_product }}">
                                                </td>
                                                <td>
                                                    {{ $product->sales_quentity }}
                                                    <input type="hidden" name="buying_quantity[{{ $loop->parent->index }}][]" value="{{ $product->sales_quentity }}">
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-xs remove-product" type="button">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td class="offer p-0 align-top">
                        <div class="d-flex">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Discount Type</th>
                                        <th>Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($offer->offerDetails as $detail)
                                        @foreach($detail->offerDiscounts as $discount)
                                            <tr>
                                                <td>
                                                    {{ $discount->discount_type }}
                                                    <input type="hidden" name="discount_type[{{ $loop->parent->index }}][]" value="{{ $discount->discount_type }}">
                                                </td>
                                                <td>
                                                    {{ $discount->discount_quentity }}
                                                    <input type="hidden" name="discount_amount[{{ $loop->parent->index }}][]" value="{{ $discount->discount_quentity }}">
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-xs remove-product" type="button">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-primary btn-sm" onclick="addoffers(this, 'offers-discount')">
            <i class="fa fa-plus"></i>
            Add Offers
        </button>
    </div>
</div>