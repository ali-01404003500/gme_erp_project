<?php

namespace Modules\Sales\Services;

use Modules\Sales\Models\Quotation;
use Modules\Sales\Models\QuotationDetail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;
use Modules\CRM\Models\Customer\Settings\CustomerType;
use Illuminate\Support\Facades\Storage;
use Modules\Sales\Models\InvoiceShare;
use Modules\Sales\Models\SalesOrder;

class InvoiceShareService
{
    public function createShare(SalesOrder $invoice, ?string $customerPhone = null): InvoiceShare
    {
        // 1. PDF জেনারেট করো (তোমার existing PDF generation logic ব্যবহার করো)
        $pdfContent = app(InvoicePdfService::class)->generate($invoice);

        // 2. Storage disk: production এ 's3' ব্যবহার করো, dev এ 'local'
        $disk = config('filesystems.default'); // .env FILESYSTEM_DISK=s3
        $path = 'invoice-shares/' . now()->format('Y/m') . '/' . uniqid() . '.pdf';
        Storage::disk($disk)->put($path, $pdfContent);

        // 3. Share record তৈরি
        $share = InvoiceShare::create([
            'token' => InvoiceShare::generateToken(),
            'invoice_id' => $invoice->id,
            'pdf_path' => $path,
            'customer_phone' => $customerPhone,
            'max_views' => config('services.invoice_share.max_views'),
            'expires_at' => now()->addHours(config('services.invoice_share.expiry_hours')),
        ]);

        Log::info('Invoice share created', [
            'invoice_id' => $invoice->id,
            'token' => $share->token,
            'expires_at' => $share->expires_at,
        ]);

        return $share;
    }

    public function getShareUrl(InvoiceShare $share): string
    {
        return rtrim(config('services.invoice_share.base_url'), '/') . '/i/' . $share->token;
    }
}