<?php

namespace App\Http\Controllers;

use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Account\Models\CashTransfer;
use Modules\Account\Models\ChequeVerification;
use Modules\Account\Models\FundTransfer;
use Modules\Account\Models\IOURequisition\IOURequisitionEntry;
use Modules\Account\Models\MFSVerification;
use Modules\Account\Models\OnlineDepositVerification;
use Modules\Account\Models\Payments\MakePaymentDetail;
use Modules\Licenses\Models\CBCLicenseRequisition;
use Modules\Licenses\Models\USGOrOPGLicenseRequisition;

class AccountsQuickAccessController extends Controller
{


    public function index()
    {
        $verificationTabs = $this->verificationTabs();

        return view(
            'verification.account-quick-access',
            compact('verificationTabs')
        );
    }

    protected function verificationTabs()
    {
        return [

            [
                'key'   => 'collection',
                'title' => 'Collection',
                'route' => route('account.collections.collections.index'),
                'permission' => 'account.collections.collections.index',
            ],

            [
                'key'   => 'invoice_wise_collection',
                'title' => 'Invoice Wise Collection',
                'route' => route('account.collections.invoice-wise-collections.index'),
                'permission' => 'account.collections.invoice-wise-collections.index',
            ],

            [
                'key'   => 'emi_collection',
                'title' => 'EMI Collection',
                'route' => route('account.emi-entries.emi-collections'),
                'permission' => 'account.emi-entries.emi-collections',
            ],
          
            [
                'key'   => 'advance_cheque_collection',
                'title' => 'Advance Cheque Collection',
                'route' => route('account.advance-cheque-entries.index'),
                'permission' => 'account.advance-cheque-entries.index',
            ],

            [
                'key'   => 'loan_collection',
                'title' => 'Loan Collection',
                'route' => route('account.loan-collections.index'),
                'permission' => 'account.loan-collections.create',
            ],

            [
                'key'   => 'due_payment',
                'title' => 'Due Payment',
                'route' => route('account.payments.make-payments.index'),
                'permission' => 'account.payments.make-payments.create',
            ],

            [
                'key'   => 'petty_cash_payment',
                'title' => 'Petty Cash Payment',
                'route' => route('account.payments.petty-cash-payments.index'),
                'permission' => 'account.payments.petty-cash-payments.create',
            ],

            [
                'key'   => 'invoice_wise_payment',
                'title' => 'Invoice Wise Payment',
                'route' => route('account.payments.invoice-wise-payments.index'),
                'permission' => 'account.payments.invoice-wise-payments.create',
            ],

            [
                'key'   => 'iou_payment',
                'title' => 'IOU Payment',
                'route' => route('account.i-o-u-requisition.i-o-u-requisition-entries.index'),
                'permission' => 'account.i-o-u-requisition.i-o-u-requisition-entries.pay',
            ],

            [
                'key'   => 'broker_payment',
                'title' => 'Broker Payment',
                'route' => route('account.payments.broker-payments.index'),
                'permission' => 'account.payments.broker-payments.create',
            ],

            [
                'key'   => 'loan_payment',
                'title' => 'Loan Payment',
                'route' => route('account.payments.loan-payment.index'),
                'permission' => 'account.payments.loan-payment.payment',
            ],

            [
                'key'   => 'cash_transfer',
                'title' => 'Cash Transfer',
                'route' => route('account.cash-transfers.index'),
                'permission' => 'account.cash-transfers.create',
            ],

            [
                'key'   => 'emi_entry',
                'title' => 'EMI Entry',
                'route' => route('account.emi-entries.index'),
                'permission' => 'account.emi-entries.create',
            ],

            [
                'key'   => 'advance_cheque_entry',
                'title' => 'Advance Cheque Entry',
                'route' => route('account.advance-cheque-entries.index'),
                'permission' => 'account.advance-cheque-entries.create',
            ],

            [
                'key'   => 'application_entry',
                'title' => 'Application Entry',
                'route' => route('cms.application-entries.index'),
                'permission' => 'cms.application-entries.create',
            ],
 

        ];
    }

 

}