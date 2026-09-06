<?php
// app/Http/Controllers/InvoiceShareController.php

namespace Modules\Sales\Controllers;

use App\Http\Controllers\Controller;
use App\Services\InvoiceShareService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Sales\Models\InvoiceShare;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Services\SalesOrderService;


class InvoiceShareController extends Controller
{
    public function show(Request $request, string $token)
    {
        $share = InvoiceShare::where('token', $token)->first();

        if (!$share) {
            abort(404, 'Invoice not found.');
        }
        

        if ($share->isExpired()) {
            abort(410, 'This link has expired.');
        }
 
        $share->increment('view_count');

        InvoiceShare::where('id', $share->id)->update([
            'last_viewed_at' => now(),
            'last_viewed_ip' => $request->ip(),
        ]);

        Log::info('Invoice share viewed', [
            'token' => $token,
            'ip' => $request->ip(),
            'view_count' => $share->view_count,
        ]);

        
        $disk = 'invoice_shares'; // এখন 'local' থাকবে

        if (!Storage::disk($disk)->exists($share->pdf_path)) {
            abort(404, 'File not found.');
        }

        $pdfContent = Storage::disk($disk)->get($share->pdf_path);
        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="invoice.pdf"',
        ]);

    }
}