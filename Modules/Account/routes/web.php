<?php

use Illuminate\Support\Facades\Route;
use Modules\Account\Controllers\AccountAjaxController;
use Modules\Account\Controllers\AccountControlController;
use Modules\Account\Controllers\AccountController;
use Modules\Account\Controllers\AccountGroupController;
use Modules\Account\Controllers\AccountOpeningBalanceController;
use Modules\Account\Controllers\AccountReportController;
use Modules\Account\Controllers\AccountSettings\DefaultPayableReceivableController;
use Modules\Account\Controllers\AccountSetup\BankAccountController;
use Modules\Account\Controllers\AccountSetupController;
use Modules\Account\Controllers\AccountSubsidiaryController;
use Modules\Account\Controllers\AdvanceChequeEntryController;
use Modules\Account\Controllers\CashTransferController;
use Modules\Account\Controllers\CategoryController;
use Modules\Account\Controllers\ChequeVerificationController;
use Modules\Account\Controllers\Collections\CollectionController;
use Modules\Account\Controllers\ContraVoucherController;
use Modules\Account\Controllers\CustomerController;
use Modules\Account\Controllers\DamageController;
use Modules\Account\Controllers\EMIEntryController;
use Modules\Account\Controllers\FundTransferController;
use Modules\Account\Controllers\InvoiceWiseCollectionController;
use Modules\Account\Controllers\InventoryReportController;
use Modules\Account\Controllers\InvoiceWisePaymentController;
use Modules\Account\Controllers\IOURequisition\IOURequisitionEntryController;
use Modules\Account\Controllers\JournalVoucherController;
use Modules\Account\Controllers\MakePayment\PaymentController;
use Modules\Account\Controllers\MFSVerificationController;
use Modules\Account\Controllers\OnlineDepositVerificationController;
use Modules\Account\Controllers\Payments\BrokerPaymentController;
use Modules\Account\Controllers\Payments\MakePaymentController;
use Modules\Account\Controllers\Payments\PettyCashPaymentController;
use Modules\Account\Controllers\PaymentVoucherController;
use Modules\Account\Controllers\ProductController;
use Modules\Account\Controllers\PurchaseController;
use Modules\Account\Controllers\PurchaseReturnController;
use Modules\Account\Controllers\ReceiveVoucherController;
use Modules\Account\Controllers\SaleController;
use Modules\Account\Controllers\SaleReturnController;
use Modules\Account\Controllers\Setup\BankBranchController;
use Modules\Account\Controllers\Setup\BankController;
use Modules\Account\Controllers\SupplierController;
use Modules\Account\Controllers\UnitController;
use Modules\Account\Controllers\VendorBill\GeneratedVendorBillController;
use Modules\Account\Controllers\VendorBill\VendorBillSettingController;
use Modules\Account\Models\Payments\CustomerPayment;
use Modules\Account\Controllers\Payments\LoanPaymentController;
use Modules\Account\Controllers\PaymentVerificationController;
use Modules\Account\Models\MFSVerification;
use Modules\Account\Models\OnlineDepositVerification;

Route::group(['middleware' => 'auth', 'prefix' => 'account', 'as' => 'account.'], function () {

    Route::group(['prefix' => 'account-setup', 'as' => 'account-setup.'], function () {

        Route::get('account-setups', [AccountSetupController::class, 'index'])->name('account-setups.index');
        Route::get('account-groups', [AccountGroupController::class, 'index'])->name('account-groups.index');


        Route::resource('accounts', AccountController::class);
        Route::resource('account-controls', AccountControlController::class);
        Route::resource('account-subsidiaries', AccountSubsidiaryController::class);
        Route::resource('account-opening-balances', AccountOpeningBalanceController::class);

        Route::resource('banks', BankController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('bank-branches', BankBranchController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('bank-branch-data', [BankBranchController::class, 'getBranches'])->name('ajax.bank-branches');
        Route::resource('bank-accounts', BankAccountController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('bank-account-data', [BankAccountController::class, 'getAccounts'])->name('bank-accounts.get-accounts');


        // AJAX
        Route::get('account-control-data', [AccountAjaxController::class, 'getAccountControlsByAccountGroup'])->name('ajax.account-controls');
        Route::get('account-subsidiary-data', [AccountAjaxController::class, 'getAccountSubsidiariesByAccountControl'])->name('ajax.account-subsidiaries');
        Route::get('account-data', [AccountAjaxController::class, 'getAccountsByAccountControlAndAccountSubsidiary'])->name('ajax.accounts-by-control-and-subsidiary');
        Route::get('account-subsidiary-and-account-data', [AccountAjaxController::class, 'getAccountSubsidiariesAndAccountsByAccountControl'])->name('ajax.subsidiaries-and-accounts-by-control');
    });

    Route::group(['prefix' => 'account-settings', 'as' => 'account-settings.'], function () {

        Route::resource('default-payable-receivables', DefaultPayableReceivableController::class);
    });



    Route::group(['prefix' => 'collections', 'as' => 'collections.'], function () {
        Route::resource('collections', CollectionController::class);
        Route::resource('invoice-wise-collections', InvoiceWiseCollectionController::class);
        Route::get('collections-autocomplete-customers', [CollectionController::class, 'customerAutocomplete']) ->name('collections-autocomplete.customers');
    });

    Route::group(['prefix' => 'payments', 'as' => 'payments.'], function () {
        Route::resource('make-payments', MakePaymentController::class);
        Route::get('make-payments-accounts', [MakePaymentController::class, 'loadAccount'])->name('make-payments.accounts');
        Route::get('make-payments-get-ballance', [MakePaymentController::class, 'getBalance'])->name('make-payments.get-ballance');
        Route::resource('broker-payments', BrokerPaymentController::class);
        Route::get('broker-payments-verify/{id}', [BrokerPaymentController::class, 'verify'])->name('broker-payments.verify');
        Route::get('broker-payments-approve/{id}', [BrokerPaymentController::class, 'approve'])->name('broker-payments.approve');
        Route::get('broker-payments-deny/{id}', [BrokerPaymentController::class, 'deny'])->name('broker-payments.deny');

        Route::resource('invoice-wise-payments', InvoiceWisePaymentController::class);
    
        Route::post('invoice-wise-payments/{invoiceWisePayment}/approve', [InvoiceWisePaymentController::class, 'approve'])
            ->name('invoice-wise-payments.approve');

        Route::group(['prefix' => 'petty-cash-payments', 'as' => 'petty-cash-payments.'], function () {

            // List of approved petty cash for payment
            Route::get('/', [PettyCashPaymentController::class, 'index'])
                ->name('index');

            // List of approved petty cash for payment
            Route::get('list', [PettyCashPaymentController::class, 'list'])
                ->name('list');

            // View details of a petty cash for payment
            Route::get('details', [PettyCashPaymentController::class, 'details'])
                ->name('details');

            // View details of a petty cash for payment
            Route::get('show', [PettyCashPaymentController::class, 'showDetails'])
                ->name('show-details');

            // Process payment
            Route::post('/process', [PettyCashPaymentController::class, 'processPayment'])
                ->name('process');

            // Show payment receipt
            Route::get('/receipt/{id}', [PettyCashPaymentController::class, 'showReceipt'])
                ->name('receipt');
        });

        Route::get('loan-payment/index', [LoanPaymentController::class, 'index'])->name('loan-payment.index'); 
        Route::get('loan-payment/{id}/payment', [LoanPaymentController::class, 'payment']) ->name('loan-payment.payment');

        Route::get('payment-verifications/index', [PaymentVerificationController::class, 'index'])->name('payment-verifications.index'); 
        Route::post('payment-verifications/{id}/update', [PaymentVerificationController::class, 'update'])->name('payment-verifications.update');  
    });


    Route::get('/get-accounts-by-type', [CollectionController::class, 'getAccountsByType'])->name('get-accounts-by-type');
    Route::get('/get-ballance', [CollectionController::class, 'getBallance'])->name('get-ballance');


    Route::group(['prefix' => 'vendor-bills', 'as' => 'vendor-bills.'], function () {
        Route::resource('settings', VendorBillSettingController::class);
        Route::resource('generated-vendor-bills', GeneratedVendorBillController::class);
        // Route::resource('vendor-bills', VendorBillController::class);
    });


    Route::group(['prefix' => 'i-o-u-requisition', 'as' => 'i-o-u-requisition.'], function () {
        // Route::resource('i-o-u-requisitions', \Modules\Account\Controllers\IOURequisitionController::class);
        Route::resource('i-o-u-requisition-entries', IOURequisitionEntryController::class);

        // Additional payment routes for AJAX
        Route::post('i-o-u-requisition-entries/send-otp', [IOURequisitionEntryController::class, 'sendOTP'])->name('i-o-u-requisition-entries.send-otp');
        Route::post('i-o-u-requisition-entries/verify-otp', [IOURequisitionEntryController::class, 'verifyOTP'])->name('i-o-u-requisition-entries.verify-otp');
        Route::post('i-o-u-requisition-entries/confirm-payment', [IOURequisitionEntryController::class, 'confirmPayment'])->name('i-o-u-requisition-entries.confirm-payment');
        Route::post('i-o-u-requisition-entries/return', [IOURequisitionEntryController::class, 'returnBill'])->name('i-o-u-requisition-entries.return');
    });
    Route::resource('cash-transfers', CashTransferController::class);
    Route::post('cash-transfers/{id}/confirm', [CashTransferController::class, 'confirm'])->name('cash-transfers.confirm');



    // Route::group(['prefix' => 'payments', 'as' => 'payments.'], function () {
    //     Route::resource('customer-payments', CustomerPaymentController::class);
    //     Route::resource('supplier-payments', SupplierPaymentController::class);
    // });
    
 
    Route::resource('fund-transfers', FundTransferController ::class); 
    Route::get('fund-transfers-get-accounts', [FundTransferController::class, 'getAccount'])->name('fund-transfers.getAccounts');
    
    Route::group(['prefix' => 'reports'], function () {


        Route::get('account-ledger', [AccountReportController::class, 'accountLedgerReport'])->name('report.account-ledger');




        Route::get('chart-of-account', [AccountReportController::class, 'chartOfAccountReport'])->name('report.chart-of-account');
        Route::get('ledger-journal', [AccountReportController::class, 'JournalReport'])->name('report.ledger-journal');
        Route::get('transaction-ledger', [AccountReportController::class, 'transactionLedgerReport'])->name('report.transaction-ledger');
        Route::get('subsidiary-wise-ledger', [AccountReportController::class, 'subsidiaryWiseLedgerReport'])->name('report.subsidiary-wise-ledger');
        Route::get('nominal-account-ledger', [AccountReportController::class, 'nominalAccountLedgerReport'])->name('report.nominal-account-ledger');


        Route::get('customer-ledger', [AccountReportController::class, 'customerLedgerReport'])->name('report.customer-ledger');
        Route::get('supplier-ledger', [AccountReportController::class, 'supplierLedgerReport'])->name('report.supplier-ledger');
        Route::get('vendor-ledger', [AccountReportController::class, 'vendorLedgerReport'])->name('report.vendor-ledger');


        Route::get('supplier-report', [AccountReportController::class, 'supplierReport'])->name('report.supplier');


        Route::get('account-receivable', [AccountReportController::class, 'accountReceivableReport'])->name('report.account-receivable');
        Route::get('account-payable', [AccountReportController::class, 'accountPayableReport'])->name('report.account-payable');
        Route::get('employee-cash-handling', [AccountReportController::class, 'employeeCashHandlingReport'])->name('report.employee-cash-handling');

       

        Route::get('revenue-analysis', [AccountReportController::class, 'revenueAnalysisReport'])->name('report.revenue-analysis');
        Route::get('expense-analysis', [AccountReportController::class, 'expenseAnalysisReport'])->name('report.expense-analysis');
        Route::get('ratio-analysis', [AccountReportController::class, 'ratioAnalysisReport'])->name('report.ratio-analysis');
        Route::get('received-payment-statement', [AccountReportController::class, 'receivedPaymentStatementReport'])->name('report.received-payment-statement');



        Route::get('voucher-report', [AccountReportController::class, 'getVoucherReport'])->name('report.voucher-report');







        Route::group(['prefix' => 'financial-statements'], function () {

            Route::get('trial-balance', [AccountReportController::class, 'trialBalanceReport'])->name('report.trial-balance');
            Route::get('income-statement', [AccountReportController::class, 'incomeStatement'])->name('report.income-statement');
            Route::get('equity-statement', [AccountReportController::class, 'equityStatement'])->name('report.equity-statement');
            Route::get('balance-sheet', [AccountReportController::class, 'balanceSheetReport'])->name('report.balance-sheet');
            Route::get('cash-flow', [AccountReportController::class, 'cashFlowReport'])->name('report.cash.flow');
        });



        Route::group(['prefix' => 'inventory'], function () {


            Route::get('item-ledger', [InventoryReportController::class, 'getItemLedger'])->name('account.item-ledger');
            Route::get('stock-in-hand', [InventoryReportController::class, 'getStockInHand'])->name('account.stock-in-hand');

        });
    });





    Route::resource(name: 'emi-entries', controller: EMIEntryController::class);
    Route::get('get-invoices', [EMIEntryController::class, 'getInvoices'])->name('get-invoices');
    Route::post('emi-entrie-ajax-store', [EMIEntryController::class, 'storeAjax'])->name('emi-entries.ajax-store');
    Route::get('emi-collections', [EMIEntryController::class, 'emiCollection'])->name('emi-entries.emi-collections');
    Route::get('emi-collections/getCustomerEmis', [EMIEntryController::class, 'getCustomerEmis'])->name('emi-collections.getCustomerEmis');
    Route::get('emi-collections/getEmiDetails', [EMIEntryController::class, 'getEmiDetails'])->name('emi-collections.getEmiDetails');
    Route::get('emi-collections/getEarlySettlementDetails', [EMIEntryController::class, 'getEarlySettlementDetails'])->name('emi-collections.getEarlySettlementDetails');
    Route::get('emi-collections/getRescheduleDetails', [EMIEntryController::class, 'getRescheduleDetails'])->name('emi-collections.getRescheduleDetails');
    Route::post('emi-collections/reschedule-store', [EMIEntryController::class, 'rescheduleStore'])->name('emi-collections.reschedule-store');
    Route::post('emi-collections/collection-store', [EMIEntryController::class, 'collectionStore'])->name('emi-collections.collection-store');
    Route::post('emi-collections/settlement-collection-store', [EMIEntryController::class, 'settlementCollectionStore'])->name('emi-collections.settlement-collection-store');
    Route::post('emi-collections/rollback', [EMIEntryController::class, 'rollback'])->name('emi-collections.rollback');
    Route::get('emi-collections/showMoneyReceipt/{id}', [EMIEntryController::class, 'showMoneyReceipt'])->name('emi-collections.showMoneyReceipt');
    Route::get('emi-collections/showMoneyReceipt/{id}', [EMIEntryController::class, 'showMoneyReceipt'])->name('emi-collections.showMoneyReceipt');

    Route::get('emi-reports/emi-installment-report', [EMIEntryController::class, 'emiInstallmentReport'])->name('emi-reports.emi-installment-report');
    Route::get('emi-reports/emi-report-data', [EMIEntryController::class, 'emiReportData'])->name('emi-reports.emi-report-data');

    Route::get('emi-reports/emi-customer-report', [EMIEntryController::class, 'emiCustomerWiseReport'])->name('emi-reports.emi-customer-report');



    Route::resource('advance-cheque-entries', AdvanceChequeEntryController::class);
    Route::get('advance-cheque-entries-check/{id}', [AdvanceChequeEntryController::class, 'check'])->name('advance-cheque-entries.check');
    Route::get('advance-cheque-entries-approve/{id}', [AdvanceChequeEntryController::class, 'approve'])->name('advance-cheque-entries.approve');
    Route::get('advance-cheque-entries-deny/{id}', [AdvanceChequeEntryController::class, 'deny'])->name('advance-cheque-entries.deny');
    Route::post('advance-cheque-entries/{id}/save-signature', [AdvanceChequeEntryController::class, 'saveSignature'])->name('advance-cheque-entries.save-signature');
    Route::get('advance-cheque-collections', [AdvanceChequeEntryController::class, 'chequeCollection'])->name('advance-cheque-entries.advance-cheque-collections');

    Route::get('get-customer-references', [AdvanceChequeEntryController::class, 'getCustomerReferences'])->name('get-customer-references');

    Route::resource('cheque-verifications', ChequeVerificationController::class);
    Route::post('cheque-verifications/deposit/{id}', [ChequeVerificationController::class, 'deposit'])->name('cheque-verifications.deposit');
    Route::post('cheque-verifications/cash/{id}', [ChequeVerificationController::class, 'cash'])->name('cheque-verifications.cash');
    Route::get('cheque-verifications/return/{id}', [ChequeVerificationController::class, 'chequeReturn'])->name('cheque-verifications.return');

    Route::post('cheque-verifications/status/{id}', [ChequeVerificationController::class, 'updateStatus'])->name('cheque-verifications.status');

    
    Route::resource('online-deposit-verifications', OnlineDepositVerificationController::class); 
    Route::post('online-deposit-verifications/status/{id}', [OnlineDepositVerificationController::class, 'updateStatus'])->name('online-deposit-verifications.update-status');
 
    Route::resource('mfs-verifications', MFSVerificationController::class);
    Route::post('mfs-verifications/status/{id}', [MFSVerificationController::class, 'updateStatus'])->name('mfs-verifications.update-status');



    // Product
    Route::group(['prefix' => 'product'], function () {
        Route::resource('units', UnitController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('damages', DamageController::class);
    });










    // Party
    Route::group(['prefix' => 'party'], function () {
        Route::resource('acc-customers', CustomerController::class);
        Route::resource('acc-suppliers', SupplierController::class);
    });










    // Purchase
    // Route::group(['prefix' => 'purchase'], function () {
    //     Route::resource('acc-payments', PaymentController::class);
    //     Route::resource('acc-purchases', PurchaseController::class);
    //     Route::resource('acc-purchase-returns', PurchaseReturnController::class);

    //     Route::get('acc-returnable-purchase-invoices', [PurchaseReturnController::class, 'getReturnablePurchaseInvoices'])->name('acc-returnable-purchase-invoices');
    //     Route::get('acc-returnable-purchase-items', [PurchaseReturnController::class, 'getReturnablePurchaseItems'])->name('acc-returnable-purchase-items');
    // });










    // Sale
    Route::group(['prefix' => 'sale'], function () {
        // Route::resource('acc_collections', CollectionController::class);
        Route::resource('acc-sales', SaleController::class);
        Route::resource('acc-sale-returns', SaleReturnController::class);

        Route::get('acc-returnable-sale-invoices', [SaleReturnController::class, 'getReturnableSaleInvoices'])->name('acc-returnable-sale-invoices');
        Route::get('acc-returnable-sale-items', [SaleReturnController::class, 'getReturnableSaleItems'])->name('acc-returnable-sale-items');
    });






    // Voucher
    Route::group(['prefix' => 'voucher', 'as' => 'voucher-'], function () {


        // Receive
        Route::post('receives/{receive}/approve', [ReceiveVoucherController::class, 'approveReceiveVoucher'])->name('receives.approve');
        Route::resource('receives', ReceiveVoucherController::class);


        // Payment
        Route::post('payments/{payment}/approve', [PaymentVoucherController::class, 'approvePaymentVoucher'])->name('payments.approve');
        Route::resource('payments', PaymentVoucherController::class);


        // Contra
        Route::post('contras/{contra}/approve', [ContraVoucherController::class, 'approveContraVoucher'])->name('contras.approve');
        Route::resource('contras', ContraVoucherController::class);


        // Journal
        Route::post('journals/{journal}/approve', [JournalVoucherController::class, 'approveJournalVoucher'])->name('journals.approve');
        Route::resource('journals', JournalVoucherController::class);
    });


    // Route::group(['prefix' => 'ajax', 'as' => 'ajax-'], function () {

    //     Route::get('company-wise-product', [AjaxController::class, 'getCompanyProduct'])->name('company-product-wise');
    // });
});