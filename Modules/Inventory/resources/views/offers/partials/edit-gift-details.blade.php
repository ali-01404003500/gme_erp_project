<div class="col-md-12 offers offers-gift">
    <div class="table-responsive gift-container">
        <table class="table table-bordered" style="max-width: 100%;">
            <thead>
                <tr>
                    <th style="width:50%; text-align:center;">Buying Product</th>
                    <th style="width:50%; text-align:center;">Offers Product</th>
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
                            <button class="btn btn-primary w-100 btn-xs" type="button" onclick="addProductToOffer(this)">
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
                        Offers Products
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
                                        @foreach($detail->giftSalesProducts as $product)
                                            <tr>
                                                <td>
                                                    {{ $product->product->name }}
                                                    <input type="hidden" name="buying_product_id[{{ $loop->parent->index }}][]" value="{{ $product->product_id }}">
                                                </td>
                                                <td>
                                                    {{ $product->quantity }}
                                                    <input type="hidden" name="buying_quantity[{{ $loop->parent->index }}][]" value="{{ $product->quantity }}">
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
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($offer->offerDetails as $detail)
                                        @foreach($detail->giftOfferProducts as $product)
                                            <tr>
                                                <td>
                                                    {{ $product->product->name }}
                                                    <input type="hidden" name="offer_product_id[{{ $loop->parent->index }}][]" value="{{ $product->product_id }}">
                                                </td>
                                                <td>
                                                    {{ $product->quantity }}
                                                    <input type="hidden" name="offer_quantity[{{ $loop->parent->index }}][]" value="{{ $product->quantity }}">
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
        <button type="button" class="btn btn-primary btn-sm" onclick="addoffers(this, 'offers-gift')">
            <i class="fa fa-plus"></i>
            Add Offers
        </button>
    </div>
</div>