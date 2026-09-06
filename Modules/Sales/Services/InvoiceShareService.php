<?php

namespace Modules\Sales\Services;
 
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
    public function __construct(protected InvoicePdfService $pdfService) {}

    /**
     * @param SalesOrder $order
     * @param mixed $salesOrderService তোমার existing SalesOrderService instance
     */
    public function createShare(SalesOrder $order, $salesOrderService): InvoiceShare
    {
        $pdfContent = $this->pdfService->generate($order->id, $salesOrderService);

        $disk = 'invoice_shares'; 
        $path = 'invoice-shares/' . now()->format('Y/m') . '/' . uniqid('so_' . $order->id . '_') . '.pdf';
        Storage::disk($disk)->put($path, $pdfContent);

        $expiresAt = now()->addHours(config('services.invoice_share.expiry_hours'));
        
        $share = InvoiceShare::create([
            'token' => InvoiceShare::generateToken(),
            'sales_order_id' => $order->id,
            'pdf_path' => $path,
            'customer_phone' => $order->resolvePhone(),
            'max_views' => config('services.invoice_share.max_views'),
            'expires_at' => $expiresAt,
        ]);

    
        Log::info('Invoice share created', [
            'sales_order_id' => $order->id,
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