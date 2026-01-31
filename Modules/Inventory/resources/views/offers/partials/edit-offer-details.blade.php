@if($offer->offer_type == 'discount')
    @include('Inventory::offers.partials.edit-discount-details', ['offer' => $offer])
@elseif($offer->offer_type == 'gift')
    @include('Inventory::offers.partials.edit-gift-details', ['offer' => $offer])
@elseif($offer->offer_type == 'clearance')
    @include('Inventory::offers.partials.edit-clearance-details', ['offer' => $offer])
@endif