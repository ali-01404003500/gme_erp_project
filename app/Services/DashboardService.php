<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Account\Models\Account; 
use Modules\Purchase\Models\Requisition;
use Modules\Sales\Models\SalesOrder;

class DashboardService
{
   
    public function getSummary($type) {

        $currentSales = $previousSales = $currentPurchase = $previousPurchase = 0;
        $paidSales = $previousPaidSales = $dueSales = $previousDueSales = 0;
        $currentCollection = $previousCollection = $currentPayment = $previousPayment = 0;
        $inventoryValue = $previousInventoryValue = $totalPayable = $previousPayable = $totalReceivable = $previousReceivable = 0;
 

        if($type == 'monthly') {
 
            $currentMonth = Carbon::now()->month;
            $previousMonth = Carbon::now()->subMonth()->month;

            $currentSales = SalesOrder::whereMonth('invoice_date', $currentMonth)->sum('net_amount');
            $previousSales = SalesOrder::whereMonth('invoice_date', $previousMonth)->sum('net_amount');

            $currentPurchase = Requisition::whereMonth('invoice_date', $currentMonth)->sum('net_amount');
            $previousPurchase = Requisition::whereMonth('invoice_date', $previousMonth)->sum('net_amount');
    
            
            $currentCollection = Account::where('account_subsidiary_id', 1001)
                ->withSum(['transactions as total_amount' => function ($q) use ($currentMonth) {
                    $q->where('balance_type', 'debit')
                    ->whereMonth('transaction_date', $currentMonth);
                }], 'amount')
                ->get()
                ->sum('total_amount');


            $previousCollection = Account::where('account_subsidiary_id', 1001)
                ->withSum(['transactions as total_amount' => function ($q) use ($previousMonth) {
                    $q->where('balance_type', 'debit')
                    ->whereMonth('transaction_date', $previousMonth);
                }], 'amount')
                ->get()
                ->sum('total_amount');
            
            $currentPayment = Account::where('account_subsidiary_id',2001)->with(['transactions'=>function($q) use($currentMonth){
                $q->where('balance_type','debit')->whereMonth('transaction_date',$currentMonth);
            }])->get()->sum('transactions.amount');
 
            $previousPayment = Account::where('account_subsidiary_id',2001)->with(['transactions'=>function($q) use($previousMonth){
                $q->where('balance_type','debit')->whereMonth('transaction_date',$previousMonth);
            }])->get()->sum('transactions.amount');


            $inventoryValue = 0; 
            $previousInventoryValue = 0;

            $totalPayable = 0; 
            $previousPayable = 0;

            $totalReceivable = 0;  
            $previousReceivable = 0; 


        } else { // daily  
            $currentSales = SalesOrder::whereDate('invoice_date', today())->sum('net_amount');
            $previousSales = SalesOrder::whereDate('invoice_date', Carbon::yesterday())->sum('net_amount');

            $paidSales = SalesOrder::whereDate('invoice_date', today())->where('paid_status', 'paid')->sum('net_amount');
            $previousPaidSales = SalesOrder::whereDate('invoice_date', Carbon::yesterday())->where('paid_status', 'paid')->sum('net_amount');

            $dueSales = SalesOrder::whereDate('invoice_date', today()) ->where('paid_status', 'unpaid')->sum('net_amount');
            $previousDueSales = SalesOrder::whereDate('invoice_date', Carbon::yesterday())->where('paid_status', 'unpaid')->sum('net_amount');

            $currentPurchase = Requisition::whereDate('invoice_date', today())->sum('net_amount');
            $previousPurchase = Requisition::whereDate('invoice_date', Carbon::yesterday())->sum('net_amount');

            $currentCollection = Account::where('account_subsidiary_id', 1001)
                ->withSum(['transactions as total_amount' => function ($q) {
                    $q->where('balance_type', 'debit')->whereDate('transaction_date', today());
                }], 'amount')
                ->get()->sum('total_amount');
  

            $previousCollection = Account::where('account_subsidiary_id', 1001)
                ->withSum(['transactions as total_amount' => function ($q) {
                    $q->where('balance_type', 'debit')->whereDate('transaction_date', Carbon::yesterday());
                }], 'amount')
                ->get()->sum('total_amount');
                
            $currentPayment = Account::where('account_subsidiary_id',2001)->with(['transactions'=>function($q){
                $q->where('balance_type','credit')->whereDate('transaction_date',today());
            }])->get()->sum('transactions.amount');
 
            $previousPayment = Account::where('account_subsidiary_id',2001)->with(['transactions'=>function($q){
                $q->where('balance_type','credit')->whereDate('transaction_date',Carbon::yesterday());
            }])->get()->sum('transactions.amount');
        }

        $data = [ 
            'currentSales'=>$currentSales,
            'previousSales'=>$previousSales,
            'paidSales'=>$paidSales,
            'previousPaidSales'=>$previousPaidSales,
            'dueSales'=>$dueSales,
            'previousDueSales'=>$previousDueSales, 
            'currentPurchase'=>$currentPurchase,
            'previousPurchase'=>$previousPurchase,
            'currentCollection'=>$currentCollection,
            'previousCollection'=>$previousCollection,
            'currentPayment'=>$currentPayment,
            'previousPayment'=>$previousPayment,
            'inventoryValue'=>$inventoryValue,
            'previousInventoryValue'=>$previousInventoryValue,
            'totalPayable'=>$totalPayable,
            'previousPayable'=>$previousPayable,
            'totalReceivable'=>$totalReceivable,
            'previousReceivable'=>$previousReceivable
        ];
 


        return $data;
    }

    public function getUserSummary($type) {

        $currentSalesTarget = $previousSalesTarget = $currentSalesTargetAcv = $previousSalesTargetAcv = 0;
        $currentSalesTargetUnacv = $previousSalesTargetUnacv = $currentSalesTargetAcvPer = $previousSalesTargetAcvPer = 0;


        $currentTa = $previousTa = $currentDa = $previousDa = 0;
        $currentTaDa = $previousTaDa = $currentMarketDue = $previousMarketDue = 0;

     


        if($type == 'monthly')  {
             
        } elseif($type == 'yearly') {
             

        } else { // daily
            
        } 

        $data = [ 
            'currentSalesTarget'=>$currentSalesTarget,
            'previousSalesTarget'=>$previousSalesTarget,
            'currentSalesTargetAcv'=>$currentSalesTargetAcv,
            'previousSalesTargetAcv'=>$previousSalesTargetAcv,
            'currentSalesTargetUnacv'=>$currentSalesTargetUnacv,
            'previousSalesTargetUnacv'=>$previousSalesTargetUnacv,
            'currentSalesTargetAcvPer'=>$currentSalesTargetAcvPer,
            'previousSalesTargetAcvPer'=>$previousSalesTargetAcvPer,

            'currentTa'=>$currentTa,
            'previousTa'=>$previousTa,
            'currentDa'=>$currentDa,
            'previousDa'=>$previousDa,
            'currentTaDa'=>$currentTaDa,
            'previousTaDa'=>$previousTaDa,
            'currentMarketDue'=>$currentMarketDue,
            'previousMarketDue'=>$previousMarketDue
        ];
       

        return $data;
    }


    
}