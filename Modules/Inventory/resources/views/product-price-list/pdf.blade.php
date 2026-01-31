<!DOCTYPE html>
<html>
<head>
    <title>Product Price List</title>
    <style>
        body {
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Product Price List</h1>
    <table>
        <thead>
            <tr>
                @if (in_array('product_name', $selectedColumns))
                    <th>Product Name</th>
                @endif
                @if (in_array('brand', $selectedColumns))
                    <th>Brand</th>
                @endif
                @if (in_array('tags', $selectedColumns))
                    <th>Tags</th>
                @endif
                @if (in_array('first_mrp', $selectedColumns))
                    <th>First MRP</th>
                @endif
                @if (in_array('offer_price', $selectedColumns))
                    <th>Offer Price</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    @if (in_array('product_name', $selectedColumns))
                        <td>{{ $product->name }}</td>
                    @endif
                    @if (in_array('brand', $selectedColumns))
                        <td>{{ optional($product->brand)->name }}</td>
                    @endif
                    @if (in_array('tags', $selectedColumns))
                        <td>
                            @foreach ($product->tags as $tag)
                                {{ $tag->name }},
                            @endforeach
                        </td>
                    @endif
                    @if (in_array('first_mrp', $selectedColumns))
                        <td>{{ $product->first_mrp }}</td>
                    @endif
                    @if (in_array('offer_price', $selectedColumns))
                        <td>
                            @php
                                $offerPrice = $product->first_mrp;
                                if ($product->discount_percentage) {
                                    $offerPrice = $product->first_mrp - ($product->first_mrp * $product->discount_percentage / 100);
                                }
                            @endphp
                            {{ $offerPrice }}
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>