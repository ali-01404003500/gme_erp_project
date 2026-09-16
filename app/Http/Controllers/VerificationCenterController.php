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

class VerificationCenterController extends Controller
{


    public function index()
    {
        $verificationTabs = $this->verificationTabs();

        return view(
            'verification.index',
            compact('verificationTabs')
        );
    }

    protected function verificationTabs()
    {
        return [

            [
                'key'   => 'otp',
                'title' => 'OTP',
                'route' => route('verification.verification-requests'),
                'permission' => 'verification.verification-requests',
            ],

            [
                'key'   => 'bkash_checking',
                'title' => 'bKash Checking',
                'route' => route('account.mfs-verifications.index'),
                'permission' => 'account.mfs-verifications.check',
            ],

            [
                'key'   => 'online_deposit_checking',
                'title' => 'Online Deposit Checking',
                'route' => route('account.online-deposit-verifications.index'),
                'permission' => 'account.online-deposit-verifications.check',
            ],
          
            [
                'key'   => 'cheque_checking',
                'title' => 'Cheque Encash Checking',
                'route' => route('account.cheque-verifications.index'),
                'permission' => 'account.cheque-verifications.check',
            ],

            [
                'key'   => 'bkash_verify',
                'title' => 'bKash Verify',
                'route' => route('account.mfs-verifications.index'),
                'permission' => 'account.mfs-verifications.check-verification',
            ],

            [
                'key'   => 'online_deposit_verify',
                'title' => 'Online Deposit Verify',
                'route' => route('account.online-deposit-verifications.index'),
                'permission' => 'account.online-deposit-verifications.check-verification',
            ],

            [
                'key'   => 'cheque_verify',
                'title' => 'Cheque Encash Verify',
                'route' => route('account.cheque-verifications.index'),
                'permission' => 'account.cheque-verifications.check-verification',
            ],

            [
                'key'   => 'cash_verification',
                'title' => 'Cash Verification',
                'route' => route('account.cash-transfers.index'),
                'permission' => 'account.cash-transfers.confirm',
            ],

            [
                'key'   => 'cash_bank_transfer',
                'title' => 'Cash-Bank Transfer Verification',
                'route' => route('account.fund-transfers.index'),
                'permission' => 'account.fund-transfers.verify',
            ],

            [
                'key'   => 'customer_ledger',
                'title' => 'Customer Ledger',
                'route' => route('account.report.customer-ledger'),
                'permission' => 'account.report.customer-ledger',
            ],

            [
                'key'   => 'payment_checking',
                'title' => 'Payment Checking',
                'route' => route('account.payments.payment-verifications.index'),
                'permission' => 'account.payments.payment-verifications.checking',
            ],

            [
                'key'   => 'payment_approved',
                'title' => 'Payment Approved',
                'route' => route('account.payments.payment-verifications.index'),
                'permission' => 'account.payments.payment-verifications.verify',
            ],

            [
                'key'   => 'cbc_license_verification',
                'title' => 'CBC License Verification',
                'route' => route('licenses.cbc-license-requisitions.index'),
                'permission' => 'licenses.cbc-license-requisitions.approve',
            ],

            [
                'key'   => 'usg_license_verification',
                'title' => 'USG License Verification',
                'route' => route('licenses.usg-opg-license-requisitions.index'),
                'permission' => 'licenses.usg-opg-license-requisitions.approve',
            ],


            [
                'key'   => 'cbc_license_key_send',
                'title' => 'CBC License Key Send',
                'route' => route('licenses.cbc-sms.index'),
                'permission' => 'licenses.cbc-sms.update',
            ],

            [
                'key'   => 'usg_license_key_send',
                'title' => 'USG License Key Send',
                'route' => route('licenses.usg-opg-sms.index'),
                'permission' => 'licenses.usg-opg-sms.update',
            ],

          
            // [
            //     'key'   => 'waiver_verification',
            //     'title' => 'Waiver Verification',
            //     'route' => route('YOUR_ROUTE_NAME'),
            // ],

            // [
            //     'key'   => 'waiver_approved',
            //     'title' => 'Waiver Approved',
            //     'route' => route('YOUR_ROUTE_NAME'),
            // ],

            [
                'key'   => 'iou_verification',
                'title' => 'IOU Verification',
                'route' => route('account.i-o-u-requisition.i-o-u-requisition-entries.index'),
                'permission' => 'account.i-o-u-requisition.i-o-u-requisition-entries.approve',
            ],

            [
                'key'   => 'iou_approve',
                'title' => 'IOU Approve',
                'route' => route('account.i-o-u-requisition.i-o-u-requisition-entries.index'),
                'permission' => 'account.i-o-u-requisition.i-o-u-requisition-entries.verify',
            ],

        ];
    }


    /**
    * Verification pending counts
    */
    public function counts()
    {
        $otp = OtpVerification::query()->where('status', 'pending')->count();

        $bkashChecking = MFSVerification::query()->where('status', 'pending')->count();
        $bkashVerify = MFSVerification::query()->where('status', 'verified')->count();

        $onlineDepositChecking = OnlineDepositVerification::query()->where('status', 'pending')->count();
        $onlineDepositVerify = OnlineDepositVerification::query()->where('status', 'verified')->count();

        $chequeChecking = OnlineDepositVerification::query()->where('status', 'verified')->count();
        $chequeVerify = OnlineDepositVerification::query()->where('status', 'honored-verified')->count();

        $cashVerification = 0;

        $cashBankTransfer = 0;

        $customerLedger = '';


        $paymentChecking = MakePaymentDetail::query()->where('verified', '0')->count();
        $paymentApproved = MakePaymentDetail::query()->where('verified', '1')->count();


        $cbcLicenseVerification = CBCLicenseRequisition::query()->where('status', 'Pending')->count();
        $usgLicenseVerification = USGOrOPGLicenseRequisition::query()->where('status', 'Pending')->count();
        $cbcLicenseKeySend = CBCLicenseRequisition::query()->where('status', 'Approved')->count();
        $usgLicenseKeySend = USGOrOPGLicenseRequisition::query()->where('status', 'Approved')->count(); 

        $iouVerification = IOURequisitionEntry::query()->where('status', 'pending')->count();
        $iouApprove = IOURequisitionEntry::query()->where('status', 'verified')->count();


        return response()->json([

            'success' => true,
            'counts' => [
                'additional_limit'        => 0,

                'otp'                     => $otp,

                'bkash_checking'          => $bkashChecking,
                'online_deposit_checking' => $onlineDepositChecking,
                'cheque_checking'         => $chequeChecking,

                'bkash_verify'            => $bkashVerify,
                'online_deposit_verify'   => $onlineDepositVerify,
                'cheque_verify'           => $chequeVerify,

                'cash_verification'       => $cashVerification,
                'cash_bank_transfer'      => $cashBankTransfer,

                'customer_ledger'         => $customerLedger,

                'payment_checking'        => $paymentChecking,
                'payment_approved'       => $paymentApproved,

                'cbc_license_verification' => $cbcLicenseVerification,
                'usg_license_verification' => $usgLicenseVerification,

                'cbc_license_key_send'    => $cbcLicenseKeySend,
                'usg_license_key_send'    => $usgLicenseKeySend,

                'waiver_verification'     => 0,
                'waiver_approved'         => 0,

                'iou_verification'        => $iouVerification,
                'iou_approve'        => $iouApprove,
            ],

        ]);
    }

}