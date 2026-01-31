<?php

namespace Modules\CRM\Controllers\Customer\Settings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CRM\Models\Customer\Customer;
use Modules\CRM\Models\Customer\Settings\CustomerShipping;
use Modules\CRM\Services\Customer\Settings\CustomerShippingService;

class CustomerShippingController extends Controller
{

    /**
     * Service variable
     *
     * @var CustomerShippingService
     */
    private $service; 
    function __construct(CustomerShippingService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['customerShippings'] = $this->service->getAll();

        return view("CRM::settings.customer-shipping.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['customers'] = Customer::where('status', 1)->get();

        return view('CRM::settings.customer-shipping.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'customer_id' => 'required',
            'division_id' => 'required',
            'district_id' => 'required',
            'contact_person_name' => 'required',
            'contact_person_mobile' => 'required',
            'contact_person_email' => 'nullable|email|max:255',
            'address' => 'required',
        ]);
        try{
            $this->service->create($validate);
            return redirect()->route('crm.customer-shippings.index')->with('success', 'Customer Shipping created successfully.');
        } catch(\Exception $e){
            return redirect()->route('crm.customer-shippings.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['customerShipping'] = $this->service->show($id);

        return view("customerShippings.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerShipping $customerShipping)
    {
        $data['customer_shipping'] = $customerShipping;
        $data['customers'] = Customer::where('status', 1)->get();
        //
        return view("CRM::settings.customer-shipping.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerShipping $customerShipping)
    {
        $validate = $request->validate([
            'customer_id' => 'required',
            'division_id' => 'required',
            'district_id' => 'required',
            'contact_person_name' => 'required',
            'contact_person_mobile' => 'required',
            'contact_person_email' => 'nullable|email',
            'address' => 'required',
        ]);
        $this->service->update($customerShipping, $validate);

        return redirect()->route('crm.customer-shippings.index')->with('success', 'CustomerShipping updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerShipping $customerShipping)
    {
        $this->service->delete($customerShipping);
        return redirect()->route('crm.customer-shippings.index')->with('success', 'CustomerShipping deleted successfully.');
    }
}
