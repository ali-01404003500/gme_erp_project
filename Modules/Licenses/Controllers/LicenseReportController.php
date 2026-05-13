<?php

namespace Modules\Licenses\Controllers;


use App\Http\Controllers\Controller;
use Modules\Licenses\Models\CbcSms;
use Modules\Licenses\Models\UsgOrOpgSms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\CRM\Models\Customer\Customer;

class LicenseReportController extends Controller
{
    public function index(Request $request)
    {
        $data['customer'] = Customer::find(request('customer_id'));

        $data['cbcSms'] = CbcSms::where('status', 'Send')
            ->searchByFields(['customer_id'])
            ->when($request->filled('from'), function ($qr) {
                $from = Carbon::parse(request('from'))->format('Y-m-d');
                $qr->whereRaw("DATE(start_date) >= ?", [$from]);
            })
            ->when($request->filled('to'), function ($qr) {
                $to = Carbon::parse(request('to'))->format('Y-m-d');
                $qr->whereRaw("DATE(expired_date) <= ?", [$to]);
            })
            ->get();

        $data['usGOrOPGSms'] = UsgOrOpgSms::where('status', 'Send')
            ->searchByFields(['customer_id'])
            ->when($request->filled('from'), function ($qr) {
                $from = Carbon::parse(request('from'))->format('Y-m-d');
                $qr->whereRaw("DATE(start_date) >= ?", [$from]);
            })
            ->when($request->filled('to'), function ($qr) {
                $to = Carbon::parse(request('to'))->format('Y-m-d');
                $qr->whereRaw("DATE(expired_date) <= ?", [$to]);
            })
            ->get();

        // Ensure both collections are not empty
        if ($data['cbcSms']->isEmpty() && $data['usGOrOPGSms']->isEmpty()) {
            $merged = collect(); // Handle empty case
        } else {
            // Merge the collections
            $merged = $data['cbcSms']->concat($data['usGOrOPGSms']);
        }

        // Order the merged collection by 'start_date' in descending order
        $ordered = $merged->sortByDesc('created_at')->values();

        // Paginate the ordered collection manually
        $page = $request->get('page', 1); // Get the current page or default to 1
        $perPage = 20; // Number of items per page
        $offset = ($page - 1) * $perPage;

        $paginated = new LengthAwarePaginator(
            $ordered->slice($offset, $perPage)->values(), // Slice the collection for the current page
            $ordered->count(), // Total number of items in the collection
            $perPage, // Items per page
            $page, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // For proper pagination links
        );

        $data['reports'] = $paginated;

        return view('Licenses::report.index', $data);
    }


}
