<?php

namespace Modules\Account\Services;

use Modules\Account\Models\InvoiceWisePayment;
use Modules\Account\Models\Payments\BrokerPayment;
use Modules\Account\Models\Payments\MakePayment;
use Modules\Account\Models\Payments\MakePaymentDetail;
use Modules\Account\Models\Payments\PettyCashPayment;
use Modules\Account\Models\PaymentVerification;
use Modules\Account\Services\Payments\BrokerPaymentService;
use Modules\Account\Services\Payments\MakePaymentService;
use Modules\Account\Services\Payments\PettyCashPaymentService;
use Modules\HRMS\Models\Loan;
use Modules\HRMS\Services\LoanService;
 
class PaymentVerificationService
{
    
    public function getAll(int $limit = 20) {
        return MakePaymentDetail::whereNotIn('verified', ['2','-1'])->paginate($limit);
    }
    
    public function store(array $data)
    {
        return MakePaymentDetail::create($data);
    }

    public function update(MakePaymentDetail $paymentVerification, array $data)
    {
        $paymentVerification->update($data);
        if($data['verified'] == '1' || $data['verified'] == '-1') { 
            $paymentVerification->update(['verified_by' => auth()->id(), 'verified_date' => now()]); 
        }

        // Additional logic based on the 'verified' status
        if($data['verified'] == '2') {  
            if($paymentVerification->paymentable_type === MakePayment::class) {
                $paymentVerification->paymentable->update(['status' => 'approved']);
                app(MakePaymentService::class)->makeDummyTransaction($paymentVerification->paymentable);
            }
            else if($paymentVerification->paymentable_type === InvoiceWisePayment::class) {
                $paymentVerification->paymentable->update(['status' => 'approved']);
                app(InvoiceWisePaymentService::class)->makeDummyTransaction($paymentVerification->paymentable);  
            }
            else if ($paymentVerification->paymentable_type === BrokerPayment::class) {
                app(BrokerPaymentService::class)->approve($paymentVerification->paymentable->id);
            }  
            else if ($paymentVerification->paymentable_type === Loan::class) {
                $paymentVerification->paymentable->update([ 'status' => 'paid']); 
                app(LoanService::class)->makeDummyTransaction($paymentVerification->paymentable);
            } 
            else if ($paymentVerification->paymentable_type === PettyCashPayment::class) { 
                app(PettyCashPaymentService::class)->makeDummyTransaction($paymentVerification->paymentable);
            } 
           
        } 
        if($data['verified'] == '-1') {
            if($paymentVerification->paymentable_type === MakePayment::class) {
                $paymentVerification->paymentable->update(['status' => 'deny']);
            }
            else if($paymentVerification->paymentable_type === InvoiceWisePayment::class) {
                $paymentVerification->paymentable->update(['status' => 'deny']);
            }
        }
        return $paymentVerification;
    }

    public function delete(MakePaymentDetail $paymentVerification)
    {
        $paymentVerification->delete();
    }

    public function show($id)
    {
        return MakePaymentDetail::findOrFail($id);
    }
}
