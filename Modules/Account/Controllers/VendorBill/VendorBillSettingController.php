<?php

namespace Modules\Account\Controllers\VendorBill;

use App\Http\Controllers\Controller;
use Modules\Account\Models\VendorBill\VendorBillSetting;
use Modules\Account\Services\VendorBill\VendorBillSettingService;
use Illuminate\Http\Request;
use Modules\CRM\Models\Customer\Customer;
use Modules\HRMS\Models\Employee;
use Modules\Purchase\Models\Vendor;

class VendorBillSettingController extends Controller
{
    /**
     * Service variable
     *
     * @var VendorBillSettingService
     */
    private $service;

    /**
     * Constructor to inject service
     */
    public function __construct(VendorBillSettingService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['vendorBillSettings'] = $this->service->getAll(10); // Paginate with 10 items per page

        return view('Account::vendor-bill.settings.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Account::vendor-bill.settings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'holder_type' => 'required|in:vendor,employee,client,others',
            'related_id' => 'required_if:holder_type,vendor,employee,client|nullable|integer',
            'bill_type' => 'required|in:Prepaid,Postpaid',
            'schedule_type' => 'required|in:Daily,Monthly,Yearly,Static',
            'schedule_value' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'status' => 'required|in:Running,Stop',
            'remarks' => 'nullable|string|max:1000',
        ];

        if ($request->holder_type !== 'others') {
            $rules['related_id'] .= '|exists:' . $this->getMorphClass($request->holder_type) . ',id';
        }

        $validated = $request->validate($rules);

        // Resolve morph class and find model
        if ($validated['holder_type'] !== 'others') {
            $morphClass = $this->getMorphClass($validated['holder_type']);
            $relatedModel = $morphClass::find($validated['related_id']);

            if (!$relatedModel) {
                return back()->withErrors(['related_id' => 'Invalid reference.'])->withInput();
            }

            $vendorBillSettingData = [
                'bill_for_id' => $relatedModel->id,
                'bill_for_type' => $morphClass,
            ];
        } else {
            $vendorBillSettingData = [
                'bill_for_id' => null,
                'bill_for_type' => null,
            ];
        }

        $data = array_merge([
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'holder_type' => $validated['holder_type'],
            'bill_type' => $validated['bill_type'],
            'schedule_type' => $validated['schedule_type'],
            'schedule_value' => $validated['schedule_value'],
            'start_date' => $validated['start_date'],
            'status' => $validated['status'],
            'remarks' => $validated['remarks'] ?? null,
        ], $vendorBillSettingData);

        $this->service->store($data);

        return redirect()->route('account.vendor-bills.settings.index')->with('success', 'Vendor bill setting created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['vendorBillSetting'] = $this->service->show($id);

        return view('Account::vendor-bill.settings.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['vendorBillSetting'] = $this->service->show($id);

        return view('Account::vendor-bill.settings.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $vendorBillSetting = $this->service->show($id);

        $rules = [
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'holder_type' => 'required|in:vendor,employee,client,others',
            'related_id' => 'required_if:holder_type,vendor,employee,client|nullable|integer',
            'bill_type' => 'required|in:Prepaid,Postpaid',
            'schedule_type' => 'required|in:Daily,Monthly,Yearly,Static',
            'schedule_value' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'status' => 'required|in:Running,Stop',
            'remarks' => 'nullable|string|max:1000',
        ];

        if ($request->holder_type !== 'others') {
            $rules['related_id'] .= '|exists:' . $this->getMorphClass($request->holder_type) . ',id';
        }

        $validated = $request->validate($rules);

        // Resolve morph class and find model
        if ($validated['holder_type'] !== 'others') {
            $morphClass = $this->getMorphClass($validated['holder_type']);
            $relatedModel = $morphClass::find($validated['related_id']);

            if (!$relatedModel) {
                return back()->withErrors(['related_id' => 'Invalid reference.'])->withInput();
            }

            $vendorBillSettingData = [
                'bill_for_id' => $relatedModel->id,
                'bill_for_type' => $morphClass,
            ];
        } else {
            $vendorBillSettingData = [
                'bill_for_id' => null,
                'bill_for_type' => null,
            ];
        }

        $data = array_merge([
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'bill_type' => $validated['bill_type'],
            'schedule_type' => $validated['schedule_type'],
            'schedule_value' => $validated['schedule_value'],
            'start_date' => $validated['start_date'],
            'status' => $validated['status'],
            'remarks' => $validated['remarks'] ?? null,
        ], $vendorBillSettingData);

        $this->service->update($vendorBillSetting, $data);

        return redirect()->route('account.vendor-bills.settings.index')->with('success', 'Vendor bill setting updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vendorBillSetting = $this->service->show($id);
        $this->service->delete($vendorBillSetting);

        return redirect()->route('account.vendor-bills.settings.index')->with('success', 'Vendor bill setting deleted successfully.');
    }


    /**
     * Map holder_type to actual FQCN for polymorphic relation
     */
    private function getMorphClass(string $holderType): string
    {
        return match ($holderType) {
            'vendor' => Vendor::class,
            'employee' => Employee::class,
            'client' => Customer::class,
            // 'others' =>OthersAccount::class, // or any model for "Others"
            default => throw new \InvalidArgumentException("Invalid holder_type: $holderType")
        };
    }
}