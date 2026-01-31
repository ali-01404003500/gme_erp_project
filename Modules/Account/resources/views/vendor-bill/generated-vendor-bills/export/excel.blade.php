<table>
    <thead>
        <tr>
            <th>Bill No</th>
            <th>Bill For</th>
            <th>Source</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $generatedVendorBill->bill_id }}</td>
            <td>{{ $generatedVendorBill->billFor?->company_name ?? $generatedVendorBill->billFor?->title ?? 'N/A' }}
            </td>
            <td>{{ $generatedVendorBill->title }}</td>
            <td>{{ \Carbon\Carbon::parse($generatedVendorBill->bill_date)->format('d-M-Y') }}</td>
            <td>{{ $generatedVendorBill->amount }}</td>
            <td>{{ $generatedVendorBill->remarks }}</td>
        </tr>
    </tbody>
</table>