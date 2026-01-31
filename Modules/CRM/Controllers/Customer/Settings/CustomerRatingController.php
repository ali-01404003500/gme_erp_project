<?php

namespace Modules\CRM\Controllers\Customer\Settings;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use Modules\CRM\Models\Customer\Settings\CustomerRating;
use Modules\CRM\Services\Customer\Settings\CustomerRatingService;

class CustomerRatingController extends Controller
{

    /**
     * Service variable
     *
     * @var CustomerRatingService
     */
    private $service; 
    function __construct(CustomerRatingService $service)
    {
        $this->service = $service;

        $this->middleware('permited');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['customerRatings'] = $this->service->getAll();

        return view("CRM::settings.customer-rating.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('CRM:::customerRatings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
           'name' => 'required|unique:customer_ratings,name,NULL,id,deleted_at,NULL',
            'code' => 'required',
            'status' => 'required',
        ]);
        $this->service->create($validate);
        return redirect()->route('crm.customer-ratings.index')->with('success', 'CustomerRating created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['customerRating'] = $this->service->show($id);

        return view("customerRatings.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerRating $customerRating)
    {
        $data['customerRating'] = $customerRating;
        //
        return view("customerRatings.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerRating $customerRating)
    {
        $validate = $request->validate([
            'name' => 'required|unique:customer_ratings,name,' . $customerRating->id . ',id,deleted_at,NULL',
            'code' => 'required',
            'status' => 'required',
        ]);
        $this->service->update($customerRating, $validate);

        return redirect()->route('crm.customer-ratings.index')->with('success', 'CustomerRating created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerRating $customerRating)
    {
        $this->service->delete($customerRating);
        return redirect()->route('crm.customer-ratings.index')->with('success', 'CustomerRating deleted successfully.');
    }
}
