<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\HRMS\Models\Employee;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function myProfile()
    {
        $user = auth()->user();
        return view('users.profile', compact('user'));
    }

    public function profilePhotographUpload(Request $request)
    {
        // dd(auth()->user()->employee);
        $employee = Employee::find(@auth()->user()->employee->id);
        if ($employee) {
            $photograph = $this->uploadFile($request->file('avatar'));
            $employee->photograph = $photograph;
            $employee->save();
        }

        return redirect()->back()->with("success", "Profiles Uploaded successfully");
    }
    public function changePassword(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->with('error', 'Old password does not match');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully');
    }

    public function importJson(Request $request)
    {
        $name = $request->input('name');
        $useDirectData = $request->has('data'); // Check if direct data is provided

        switch ($name) {
            case 'Customer Collection':
                $collectionService = new \Modules\Account\Services\Collections\CollectionService();
                if ($useDirectData) {
                    return $collectionService->handleDirectImport($collectionService, $request->input('data'));
                }
                $collectionService->storeFromJsonFile();
                break;

            case 'Supplier Payment':
                $paymentService = new \Modules\Account\Services\Payments\MakePaymentService();
                if ($useDirectData) {
                    return $paymentService->handleDirectImport($request->input('data'));
                }
                $paymentService->storeFromJsonFile();
                break;

            case 'Vendor Payment':
                $paymentService = new \Modules\Account\Services\Payments\MakePaymentService();
                if ($useDirectData) {
                    return $paymentService->handleDirectImport($request->input('data'));
                }
                $paymentService->storeFromJsonFile();
                break;

            case 'Advance Cheque Entry':
                $advance_cheque_service = app(\Modules\Account\Services\AdvanceChequeEntryService::class);
                if ($useDirectData) {
                    return $advance_cheque_service->handleDirectImport($request->input('data'));
                }
                $advance_cheque_service->storeFromJsonFile();
                break;

            case 'EMI Entry':
                $emi_service = app(\Modules\Account\Services\EMIEntryService::class);
                if ($useDirectData) {
                    return $emi_service->handleDirectImport($request->input('data'));
                }
                $emi_service->storeFromJsonFile();
                break;

            case 'Service Entry':
                $serviceEntryService = app(\Modules\Services\Services\ServiceService::class);
                if ($useDirectData) {
                    return $serviceEntryService->handleDirectImport($request->input('data'));
                }
                $serviceEntryService->storeFromJsonFile();
                break;

            case 'Office Purchase':
                $officePurchaseService = app(\Modules\Purchase\Services\OfficePurchaseService::class);
                if ($useDirectData) {
                    return $this->handleDirectImport($officePurchaseService, $request->input('data'));
                }
                $officePurchaseService->storeFromJsonFile();
                break;

            case 'Legal Entry':
                $legalEntryService = app(\Modules\Legal\Services\LegalEntryService::class);
                if ($useDirectData) {
                    return $legalEntryService->handleDirectImport($request->input('data'));
                }
                $legalEntryService->storeFromJsonFile();
                break;

            case 'Legal Schedule Update':
                $legalScheduleUpdateService = app(\Modules\Legal\Services\LegalEntryService::class);
                if ($useDirectData) {
                    $legalScheduleUpdateService->storeFromJsonFileForSchedule($request->input('data'));
                } else {
                    $legalScheduleUpdateService->storeFromJsonFileForSchedule();
                }
                break;

            case 'Bank to cash / Bank Transfer [Contra Voucher]':

                break;

            case 'Dongol Entry':
                $dongolEntryService = new \Modules\Licenses\Services\DongleOrSerialEntryService();
                if ($useDirectData) {
                    return $this->handleDirectImport($dongolEntryService, $request->input('data'));
                }
                $dongolEntryService->storeFromJsonFile();
                break;

            case 'Usg Opg License Requisition':
                $usgOrOPGLicenseRequisitionService = new \Modules\Licenses\Services\USGOrOPGLicenseRequisitionService();
                if ($useDirectData) {
                    return $usgOrOPGLicenseRequisitionService->handleDirectImport($request->input('data'));
                }
                $usgOrOPGLicenseRequisitionService->storeFromJsonFile();
                break;

            case 'Cbc License Requisition':
                $cbcLicenseRequisitionService = new \Modules\Licenses\Services\CBCLicenseRequisitionService();
                if ($useDirectData) {
                    return $cbcLicenseRequisitionService->handleDirectImport($request->input('data'));
                }
                $cbcLicenseRequisitionService->storeFromJsonFile();
                break;



            case 'Sales Return':
                $salesReturnService = app(\Modules\Sales\Services\SalesReturnService::class);
                if ($useDirectData) {
                    return $salesReturnService->handleDirectImport($request->input('data'));
                }
                // $salesReturnService->storeFromJsonFile();
                break;

            case 'Purchase Return':
                $purchaseReturnService = app(\Modules\Purchase\Services\PurchaseReturnService::class);
                if ($useDirectData) {
                    return $purchaseReturnService->handleDirectImport($request->input('data'));
                }
                $purchaseReturnService->storeFromJsonFile();
                break;

            case 'Petty Cash':
                $billsAndAllowanceService = app(\Modules\HRMS\Services\BillsAndAllowanceService::class);
                if ($useDirectData) {
                    return $billsAndAllowanceService->handleDirectImport($request->input('data'));
                }
                $billsAndAllowanceService->storeFromJsonFile();
                break;

            case 'Quotation':
                $quotationService = new \Modules\Sales\Services\QuotationService();
                if ($useDirectData) {
                    return $quotationService->storeFromDirectData($request->input('data'));
                }
                $quotationService->storeFromJsonFile();
                break;

            case 'Sales Requisition':
                $salesRequisitionService = app(\Modules\Sales\Services\SalesRequisitionService::class);
                //  new \Modules\Sales\Services\SalesRequisitionService(
                //     app(\Modules\Sales\Services\SalesOrderService::class),
                //     app(\App\Services\Notifications\GeneralNotificationService::class)
                // );
                if ($useDirectData) {
                    return $salesRequisitionService->directJsonImport($request->input('data'));
                }
                $salesRequisitionService->storeFromJsonFile();
                break;

            case 'Sales Commission':
                $salesCommissionService = new \Modules\Sales\Services\SalesCommissionService();
                if ($useDirectData) {
                    return $this->handleDirectImport($salesCommissionService, $request->input('data'));
                }
                // $salesCommissionService->storeFromJsonFile();
                break;

            case 'Gift-Offers':
                /**
                 * @var \Modules\Inventory\Services\OfferService
                 */
                $offerService = app(\Modules\Inventory\Services\OfferService::class);
                if ($useDirectData) {
                    return $offerService->handleDirectImport($request->input('data'));
                }
                $offerService->storeFromJsonFile();
                break;

            case 'Service Document Entries':
                $serviceDocumentEntryService = new \Modules\Services\Services\ServiceDocumentEntryService();
                if ($useDirectData) {
                    return $this->handleDirectImport($serviceDocumentEntryService, $request->input('data'));
                }
                $serviceDocumentEntryService->storeFromJsonFile();

                break;

            case 'Daily Call Record':
                $dailyCallService = new \Modules\CRM\Services\Customer\DailyCallService();
                if ($useDirectData) {
                    return $this->handleDirectImport($dailyCallService, $request->input('data'));
                }
                $dailyCallService->storeFromJsonFile();
                break;

            case 'Document Information':
                $documentInfoService = new \Modules\CMS\Services\DocumentEntryService();
                if ($useDirectData) {
                    return $this->handleDirectImport($documentInfoService, $request->input('data'));
                }
                $documentInfoService->storeFromJsonFile();

                break;

            case 'IOU':
                $iouService = new \Modules\Account\Services\IOURequisition\IOURequisitionEntryService();
                if ($useDirectData) {
                    return $this->handleDirectImport($iouService, $request->input('data'));
                }
                $iouService->storeFromJsonFile();

                break;

            case 'Shipment Verification':
                $shipmentVerifyService = new \Modules\Sales\Services\ShipmentVerifyService();
                if ($useDirectData) {
                    return $shipmentVerifyService->handleDirectImport($request->input('data'));
                }
                // $shipmentVerifyService->storeFromJsonFile();
                break;

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Name required with names substitution.',
                    'names' => [
                        'Customer Collection',
                        'Supplier Payment',
                        'Vendor Payment',
                        'Advance Cheque Entry',
                        'EMI Entry',
                        'Service Entry',
                        'Office Purchase',
                        'Legal Entry',
                        'Legal Schedule Update',
                        'Bank to cash / Bank Transfer [Contra Voucher]',
                        'Dongol Entry',
                        'Usg Opg License Requisition',
                        'Cbc License Requisition',
                        'Petty Cash',
                        'Sales Return',
                        'Purchase Return',
                        'Area',
                        'Quotation',
                        'Sales Requisition',
                        'Sales Commission',
                        'Gift/Offers',
                        'Service Document Entries',
                        'Daily Call Record',
                        'Document Information',
                        'IOU',
                        'Shipment Verification',
                    ]
                ], 422);
        }

        return response()->json(['success' => true, 'message' => 'Json file imported successfully']);
    }

    /**
     * Handle direct data import from API request
     */
    private function handleDirectImport($service, $data)
    {
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'No data provided.'
            ], 422);
        }

        $savedCount = 0;
        $errors = [];

        // Support both single object and array of objects
        $items = isset($data[0]) ? $data : [$data];

        foreach ($items as $index => $item) {
            try {
                $mappedData = $service->mapJson($item);
                $service->store($mappedData);
                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row {$index}: " . $e->getMessage();
            }
        }

        $message = "Import completed. Successfully saved: {$savedCount}";
        if (!empty($errors)) {
            $message .= '. Errors: ' . implode('; ', $errors);
        }

        return response()->json([
            'success' => empty($errors) || $savedCount > 0,
            'message' => $message,
            'saved_count' => $savedCount,
            'error_count' => count($errors),
            'errors' => $errors
        ], empty($errors) ? 200 : 207); // 207 Multi-Status if partial success
    }
}