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
        $currentCollection = $previousCollection = $currentPayment = $previousPayment = 0;

        if($type == 'monthly')  {
            // Current month
            $currentMonth = Carbon::now()->month;
            $currentYear  = Carbon::now()->year;

            $currentSales = SalesOrder::whereYear('invoice_date', $currentYear)
                ->whereMonth('invoice_date', $currentMonth)
                ->sum('net_amount');

            $previousMonth = Carbon::now()->subMonth();
            $previousSales = SalesOrder::whereYear('invoice_date', $previousMonth->year)
                ->whereMonth('invoice_date', $previousMonth->month)
                ->sum('net_amount');

            $currentPurchase = Requisition::whereYear('invoice_date', $currentYear)
                ->whereMonth('invoice_date', $currentMonth)
                ->sum('net_amount');

            $previousPurchase = Requisition::whereYear('invoice_date', $previousMonth->year)
                ->whereMonth('invoice_date', $previousMonth->month)
                ->sum('net_amount');


            $currentCollection = Account::where('account_subsidiary_id',1005)->with(['transactions'=>function($q) use($currentYear,$currentMonth){
                $q->where('balance_type','credit')->whereYear('transaction_date',$currentYear)->whereMonth('transaction_date',$currentMonth);
            }])->get()->sum('transactions.amount');
 
            $previousCollection = Account::where('account_subsidiary_id',1005)->with(['transactions'=>function($q) use($previousMonth){
                $q->where('balance_type','credit')->whereYear('transaction_date',$previousMonth->year)->whereMonth('transaction_date',$previousMonth->month);
            }])->get()->sum('transactions.amount');


            $currentPayment = Account::where('account_subsidiary_id',2001)->with(['transactions'=>function($q) use($currentYear,$currentMonth){
                $q->where('balance_type','debit')->whereYear('transaction_date',$currentYear)->whereMonth('transaction_date',$currentMonth);
            }])->get()->sum('transactions.amount');
 
            $previousPayment = Account::where('account_subsidiary_id',2001)->with(['transactions'=>function($q) use($previousMonth){
                $q->where('balance_type','debit')->whereYear('transaction_date',$previousMonth->year)->whereMonth('transaction_date',$previousMonth->month);
            }])->get()->sum('transactions.amount');
            

 

        } elseif($type == 'yearly') {
            $currentYear = Carbon::now()->year;
            $previousYear = Carbon::now()->subYear()->year;

            $currentSales = SalesOrder::whereYear('invoice_date', $currentYear)->sum('net_amount');
            $previousSales = SalesOrder::whereYear('invoice_date', $previousYear)->sum('net_amount');

            $currentPurchase = Requisition::whereYear('invoice_date', $currentYear)->sum('net_amount');
            $previousPurchase = Requisition::whereYear('invoice_date', $previousYear)->sum('net_amount');


            $currentCollection = Account::where('account_subsidiary_id',1005)->with(['transactions'=>function($q) use($currentYear){
                $q->where('balance_type','credit')->whereYear('transaction_date',$currentYear);
            }])->get()->sum('transactions.amount');
 
            $previousCollection = Account::where('account_subsidiary_id',1005)->with(['transactions'=>function($q) use($previousYear){
                $q->where('balance_type','credit')->whereYear('transaction_date',$previousYear);
            }])->get()->sum('transactions.amount');

            
            $currentPayment = Account::where('account_subsidiary_id',2001)->with(['transactions'=>function($q) use($currentYear){
                $q->where('balance_type','debit')->whereYear('transaction_date',$currentYear);
            }])->get()->sum('transactions.amount');
 
            $previousPayment = Account::where('account_subsidiary_id',2001)->with(['transactions'=>function($q) use($previousYear){
                $q->where('balance_type','debit')->whereYear('transaction_date',$previousYear);
            }])->get()->sum('transactions.amount');

        } else { // daily
            $currentSales = SalesOrder::whereDate('invoice_date', today())->sum('net_amount');
            $previousSales = SalesOrder::whereDate('invoice_date', Carbon::yesterday())->sum('net_amount');

            $currentPurchase = Requisition::whereDate('invoice_date', today())->sum('net_amount');
            $previousPurchase = Requisition::whereDate('invoice_date', Carbon::yesterday())->sum('net_amount');

            $currentCollection = Account::where('account_subsidiary_id',1005)->with(['transactions'=>function($q){
                $q->where('balance_type','credit')->whereDate('transaction_date',today());
            }])->get()->sum('transactions.amount');
 
            $previousCollection = Account::where('account_subsidiary_id',1005)->with(['transactions'=>function($q){
                $q->where('balance_type','credit')->whereDate('transaction_date',today());
            }])->get()->sum('transactions.amount');


            $currentPayment = Account::where('account_subsidiary_id',2001)->with(['transactions'=>function($q){
                $q->where('balance_type','debit')->whereDate('transaction_date',today());
            }])->get()->sum('transactions.amount');
 
            $previousPayment = Account::where('account_subsidiary_id',2001)->with(['transactions'=>function($q){
                $q->where('balance_type','debit')->whereDate('transaction_date',today());
            }])->get()->sum('transactions.amount');
        }

        $data = [ 
            'currentSales'=>$currentSales,
            'previousSales'=>$previousSales,
            'currentPurchase'=>$currentPurchase,
            'previousPurchase'=>$previousPurchase,
            'currentCollection'=>$currentCollection,
            'previousCollection'=>$previousCollection,
            'currentPayment'=>$currentPayment,
            'previousPayment'=>$previousPayment
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