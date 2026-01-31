<?php

namespace Modules\Services\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Services\Models\ServiceToken;
use Modules\Services\Models\ServiceMyTask;
use Dompdf\Dompdf;
use Dompdf\Options;

class InstallationReportController extends Controller
{
    public function index(Request $request)
    {
        $data['company_info'] = CompanyInfo::first();
        
        // Build query
        $query = ServiceToken::with([
            'service',
            'customer',
            'product',
            'engineerAssign.engineers',
            'serviceMyTask.pendingServiceTokens',
            'serviceMyTask.bills.product.tag'
        ])
        ->whereHas('serviceMyTask', function($q) {
            $q->where('action', 'Done');
        });;

        // Apply filters
        if ($request->filled('from')) {
            $query->whereHas('serviceMyTask', function($q) use ($request) {
                $q->whereDate('updated_at', '>=', $request->from);
            });
        }

        if ($request->filled('to')) {
            $query->whereHas('serviceMyTask', function($q) use ($request) {
                $q->whereDate('updated_at', '<=', $request->to);
            });
        }

        if ($request->filled('type')) {
            $workTypes = $request->type === 'Installation' 
                ? ['New Installation', 'Re Installation']
                : ['Maintenance', 'Software Update', 'Operating Training'];
            
            $query->whereIn('work_type', $workTypes);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($q) use ($search) {
                    $q->where('company_name', 'like', "%{$search}%");
                })
                ->orWhereHas('service', function($q) use ($search) {
                    $q->where('service_unique_id', 'like', "%{$search}%");
                });
            });
        }

        $data['reports'] = $query->orderBy('id', 'desc')->paginate(20);

        return view('Services::installation-service-report.index', $data);
    }

    public function details($id)
    {
        $data['company_info'] = CompanyInfo::first();
        $data['token'] = ServiceToken::with([
            'customer',
            'product',
            'engineerAssign.engineers.employementDetail.designation',
            'serviceMyTask.pendingServiceTokens',
            'serviceMyTask.bills.product.tag'
        ])->findOrFail($id);

        // Determine report type
        $workType = $data['token']->work_type;
        $isInstallation = in_array($workType, ['New Installation', 'Re Installation']);

        // Always generate PDF directly
        return $this->generatePDF($data, $isInstallation);
    }

    private function generatePDF($data, $isInstallation)
    {
        // Set up Dompdf options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        // Determine view based on report type
        $view = $isInstallation ? 'installation-report' : 'servicing-report';
        $html = view("Services::installation-service-report.{$view}", $data)->render();

        // Load HTML
        $dompdf->loadHtml($html);

        // Set Paper Size and Orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Generate filename
        $reportType = $isInstallation ? 'Installation' : 'Servicing';
        $reportNo = $data['token']->service->service_unique_id ?? 'REPORT';
        $filename = "{$reportType}_Report_{$reportNo}";

        // Output the PDF (0 = inline view, 1 = download)
        return $dompdf->stream($filename . '.pdf', array("Attachment" => 0));
    }

    private function generateReportNumber($token)
    {
        $date = $token->serviceMyTask->updated_at;
        $yearMonth = $date->format('Ym');
        
        // Get count of reports in this month
        $count = ServiceToken::whereHas('serviceMyTask', function($q) use ($date) {
            $q->whereYear('updated_at', $date->year)
              ->whereMonth('updated_at', $date->month)
              ->where('action', 'Done');
        })
        ->where('id', '<=', $token->id)
        ->count();

        return "GME-CER-{$yearMonth}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}