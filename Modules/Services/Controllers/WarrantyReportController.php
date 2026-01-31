<?php

namespace Modules\Services\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Licenses\Models\DongleOrSerialEntry;
use Modules\CRM\Models\Customer\Customer;
use Carbon\Carbon;

class WarrantyReportController extends Controller
{
    public function index()
    {
        return view('Services::warranty-check.index', [
            'customers' => Customer::activeCustomers()->get(),
            'serialNumbers' => DongleOrSerialEntry::with('product')->get()
        ]);
    }

    public function getCustomerSerials(Request $request)
    {
        $serials = DongleOrSerialEntry::where('customer_id', $request->customer_id)
            ->with('product')
            ->get()
            ->map(fn ($s) => [
                'dongle_id' => $s->dongle_id,
                'text'      => ($s->product->model ?? '') . ' - ' . $s->dongle_id
            ]);

        return response()->json($serials);
    }

    // ==============================================================
    // CUSTOMER + SERIAL => MULTIPLE POSSIBLE LIKE getWarrantyBySerial
    // ==============================================================
    public function getWarrantyByCustomer(Request $request)
    {
        $customerId = $request->customer_id;
        $serial = $request->serial_number;

        $entries = DongleOrSerialEntry::with(['customer','product'])
            ->where('customer_id', $customerId)
            ->where('dongle_id', $serial)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($entries->isEmpty()) {
            return response()->json(['error' => 'No matching record found'], 404);
        }

        if ($entries->count() === 1) {
            return response()->json($this->formatOne($entries->first(), true));
        }

        $list = $entries->map(function($e, $i){
            $info = $this->formatOne($e, $i === 0);
            $info['is_latest'] = $i === 0;
            if ($i !== 0) $info['status'] = 'inactive';
            return $info;
        });

        return response()->json($list);
    }

    // SERIAL SEARCH — UNCHANGED
    public function getWarrantyBySerial(Request $request)
    {
        $serial = $request->serial_number;

        $entries = DongleOrSerialEntry::with(['customer','product'])
            ->where('dongle_id', $serial)
            ->orderBy('created_at','desc')
            ->get();

        if ($entries->isEmpty()) {
            return response()->json(['error'=>'Serial not found'], 404);
        }

        if ($entries->count() === 1) {
            $info = $this->formatOne($entries->first(), true);
            $info['is_latest'] = true;
            return response()->json($info);
        }

        $list = $entries->map(function($e, $i){
            $info = $this->formatOne($e, $i===0);
            $info['is_latest'] = $i===0;
            if ($i !== 0) $info['status'] = 'inactive';
            return $info;
        });

        return response()->json($list);
    }

    // COMMON FORMATTER
    private function formatOne($entry, $latest = true)
    {
        $invoice = Carbon::parse($entry->created_at);
        $product = $entry->product;

        $unit = $product->warranty_period ?? 'year';
        $value = (int)($product->warranty_period_input ?? 0);

        $expiry = $invoice->copy();
        if ($unit === 'year') $expiry->addYears($value);
        elseif ($unit === 'month') $expiry->addMonths($value);
        else $expiry->addDays($value);

        $remaining = $latest ? $this->remaining($expiry) : $this->remaining($expiry);

        return [
            'customer_name' => $entry->customer->company_name ?? '',
            'serial_no'     => $entry->dongle_id,
            'product_name'  => $product->name ?? '',
            'invoice_date_formatted' => $invoice->format('d-M-Y'),
            'warranty_expiry_formatted' => $expiry->format('d-M-Y'),
            'warranty_period' => $value.' '.$unit,
            'remaining_period' => $remaining,
            'status' => $latest ? 'active' : 'inactive'
        ];
    }

    private function remaining($expiry)
    {
        if ($expiry->isPast()) return 'Expired';

        $diff = now()->diff($expiry);

        $parts = [];
        if ($diff->y) $parts[]="$diff->y years";
        if ($diff->m) $parts[]="$diff->m months";
        if ($diff->d) $parts[]="$diff->d days";

        return implode(', ', $parts);
    }
}
