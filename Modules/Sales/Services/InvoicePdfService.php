<?php

namespace Modules\Sales\Services;

use App\Models\AccessControl\CompanyInfo;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\Account\Models\Collections\Collection;
use Modules\Sales\Models\SalesOrder;

class InvoicePdfService
{
    /**
     * @param int|string $id
     * @param mixed $service তোমার existing SalesOrderService instance
     */
    public function prepareData($id, $service): array
    {
        $data['salesOrder'] = $service->show($id);
        $data['company_info'] = CompanyInfo::first();

        $invoiceDate = Carbon::parse($data['salesOrder']->invoice_date)->format('Y-m-d');

        // 1. LAST COLLECTION DATE
        $lastCollection = Collection::where('collection_from_id', $data['salesOrder']->customer_id)
            ->whereDate('collection_date', '<', $data['salesOrder']->invoice_date)
            ->latest('collection_date')
            ->first();

        $data['lastCollectionDate'] = $lastCollection?->collection_date
            ? Carbon::parse($lastCollection->collection_date)->format('d-M-Y') . ' (' . $lastCollection->total_amount . ')'
            : '-';

        // 2. PREVIOUS DUE
        $previousSales = SalesOrder::where('customer_id', $data['salesOrder']->customer_id)
            ->whereDate('invoice_date', '<', $invoiceDate)
            ->sum('total_amount');

        $previousPaid = Collection::where('collection_from_id', $data['salesOrder']->customer_id)
            ->whereDate('collection_date', '<', $invoiceDate)
            ->where('status', 'approved')
            ->sum('total_amount');

        $data['previousDue'] = $previousSales - $previousPaid;

        // 3. CURRENT INVOICE SALES
        $data['sales'] = $data['salesOrder']->net_amount ?? 0;

        // 4. CURRENT INVOICE PAID
        $currentCollections = Collection::where('collection_from_id', $data['salesOrder']->customer_id)
            ->where('source_id', $data['salesOrder']->id)
            ->get();

        $data['paid'] = $currentCollections->sum('total_amount');

        return $data;
    }

    /**
     * Raw PDF bytes রিটার্ন করে
     *
     * @param int|string $id
     * @param mixed $service তোমার existing SalesOrderService instance
     */
    public function generate($id, $service): string
    {
        set_time_limit(1000);

        $data = $this->prepareData($id, $service);
        $html = view('Sales::sales-order.view', $data)->render();

        $options = new Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($options);
 
        $fontFile = public_path('assets/fonts/SolaimanLipi.ttf');

        //$dompdf->getOptions()->set('isFontSubsettingEnabled', true);
        $dompdf->getOptions()->set('isFontSubsettingEnabled', false);
        $fontMetrics = $dompdf->getFontMetrics();
        $fontMetrics->get_font($fontFile, 'SolaimanLipi');

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}

