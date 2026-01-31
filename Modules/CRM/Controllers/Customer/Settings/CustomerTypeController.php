<?php

namespace Modules\CRM\Controllers\Customer\Settings;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Modules\CRM\Models\Customer\Customer;
use Modules\CRM\Models\Customer\Settings\CustomerType;
use Modules\CRM\Services\Customer\Settings\CustomerTypeService;

class CustomerTypeController extends Controller
{

    /**
     * Service variable
     *
     * @var CustomerTypeService
     */
    private $service; 
    function __construct(CustomerTypeService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['customerTypes'] = $this->service->getAll();

        return view("CRM::settings.customer-type.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customerTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|unique:customer_types,name,NULL,id,deleted_at,NULL',
            'code' => 'required',
            'status' => 'required',
        ]);
        $this->service->create($validate);
        return redirect()->route('crm.customer-types.index')->with('success', 'CustomerType created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['customerType'] = $this->service->show($id);

        return view("customerTypes.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerType $customerType)
    {
        $data['customerType'] = $customerType;
        //
        return view("customerTypes.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerType $customerType)
    {
        $validate = $request->validate([
            'name' => 'required',
            'code' => 'required',
            'status' => 'required',
        ]);
        $this->service->update($customerType, $validate);

        return redirect()->route('crm.customer-types.index')->with('success', 'CustomerType created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customerType = CustomerType::find($id);
        $getCustomer = Customer::where('customer_type', $customerType->id)->first();
        
        if($getCustomer){
            return redirect()->route('crm.customer-types.index')->with('error', 'CustomerType can not be deleted as it is in use.');
        }else{
            $this->service->delete($customerType);
            return redirect()->route('crm.customer-types.index')->with('success', 'CustomerType deleted successfully.');
        }
    }
}
