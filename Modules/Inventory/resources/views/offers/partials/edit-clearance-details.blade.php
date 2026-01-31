<div class="col-md-12 offers offers-clearance">
    <div class="table-responsive discount-container">
        <table class="table table-bordered" style="max-width: 100%;">
            <thead>
                <tr>
                    <th style="width:50%; text-align:center;">Buying Amount</th>
                    <th style="width:50%; text-align:center;">Discount Price</th>
                </tr>
                <tr>
                    <th>
                        <div class="fields">
                            <label for="buying_amount_from">From</label>
                            <input type="number" class="numberOnly form-control buying_amount_from">
                            <label for="buying_amount_to">To</label>
                            <input type="number" class="numberOnly form-control buying_amount_to">
                            <label for="">&nbsp;</label>
                            <button class="btn btn-primary w-100 btn-xs" type="button" onclick="addRangeToBuying(this)">
                                <i class="fa fa-plus"></i> Add
                            </button>
                        </div>
                    </th>
                    <th>
                        <div class="fields align-top">
                            <div class="input-group d-flex justify-content-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="clearance_type" 
                                        id="discount_type_discount" value="discount" onchange="checkDiscountType(this)" 
                                        @if(old('clearance_type', $offer->offerDetails->first()->offerDiscounts->count() > 0)) checked @endif>
                                    <label class="form-check-label" for="discount_type_discount">Discount</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="clearance_type" 
                                        id="discount_type_gift" value="gift" onchange="checkDiscountType(this)"
                                        @if(old('clearance_type', $offer->offerDetails->first()->giftOfferProducts->count() > 0)) checked @endif>
                                    <label class="form-check-label" for="discount_type_gift">Gift</label>
                                </div>
                            </div>
                            
                            <div class="gift-inputs" style="display:none;">
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
                            </div>
                            
                            <div class="discount-inputs">
                                <label for="discount_type">Discount Type</label>
                                <select class="form-control discount_type to-select gift-select">
                                    <option value="">Select</option>
                                    <option value="flat_discount">Flat Discount</option>
                                    <option value="percentage_discount">Percentage Discount</option>
                                </select>
                                <label class="discount-label" for="discount_amount">Discount Amount</label>
                                <input type="number" class="numberOnly form-control discount_amount discount-input">
                            </div>

                            <label for="">&nbsp;</label>
                            <button class="btn btn-primary w-100 btn-xs" type="button" onclick="addClearaceToOffer(this)">
                                <i class="fa fa-plus"></i> Add
                            </button>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="background-color: #baffdd;text-align:center;font-weight: 800;">
                        Buy Range
                    </td>
                    <td style="background-color: #baffdd;text-align:center;font-weight: 800;">
                        Offer
                    </td>
                </tr>
                <tr>
                    <td class="buying align-top p-0">
                        <div class="d-flex">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>From</th>
                                        <th>To</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($offer->offerDetails as $detail)
                                        @foreach($detail->clearanceOfferRanges as $range)
                                            <tr>
                                                <td>
                                                    {{ $range->buying_amount_from }}
                                                    <input type="hidden" name="buying_amount_from[{{ $loop->parent->index }}][]" value="{{ $range->buying_amount_from }}">
                                                </td>
                                                <td>
                                                    {{ $range->buying_amount_to }}
                                                    <input type="hidden" name="buying_amount_to[{{ $loop->parent->index }}][]" value="{{ $range->buying_amount_to }}">
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
                                        <th>Type/Product</th>
                                        <th>Amount/Quantity</th>
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
        <button type="button" class="btn btn-primary btn-sm" onclick="addoffers(this, 'offers-clearance')">
            <i class="fa fa-plus"></i>
            Add Offers
        </button>
    </div>
</div>