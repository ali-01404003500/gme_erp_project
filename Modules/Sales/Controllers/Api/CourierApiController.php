<?php

namespace Modules\Sales\Controllers\Api;


use App\Http\Controllers\Controller;
use Modules\Sales\Models\Courier;
use Modules\Sales\Services\CourierService;
use Illuminate\Http\Request;

class CourierApiController extends Controller
{

    /**
     * Service variable
     *
     * @var CourierService
     */
    private $service; 
    function __construct(CourierService $service)
    {
        $this->service = $service;
        // $this->middleware('permited');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['couriers'] = $this->service->getAll();

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
           'courier_name' => 'required|unique:couriers,courier_name,NULL,id,deleted_at,NULL',
            'courier_branch' => 'required',
            'courier_address' => 'required',
            'courier_phone' => 'required',
            'courier_email' => 'nullable|email|max:255|unique:couriers,courier_email,NULL,id,deleted_at,NULL',
            'contact_person_name' => 'nullable',
            'contact_person_phone' => 'nullable',
            'contact_person_address' => 'nullable',
            'contact_person_designation' => 'nullable',
            'status' => 'required',

        ]);
        $courier = $this->service->store($validate);
        return response()->json(['success' => 'Courier created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['courier'] = $this->service->show($id);

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Courier $courier)
    {
        $validate = $request->validate([
           'courier_name' => 'required',
            'courier_branch' => 'required',
            'courier_address' => 'required',
            'courier_phone' => 'required',
            'courier_email' => 'nullable|email',
            'contact_person_name' => 'nullable',
            'contact_person_phone' => 'nullable',
            'contact_person_address' => 'nullable',
            'contact_person_designation' => 'nullable',
            'status' => 'required',
        ]);
        $courier = $this->service->update($courier, $validate);

        return response()->json(['success' => 'Courier updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Courier $courier)
    {
        $this->service->delete($courier);
        return response()->json(['success' => 'Courier deleted successfully.']);
    }


    
    /**
     * get all couriers
     */
    public function getAllCouriers()
    {
        $data['couriers'] = Courier::select('id', 'courier_name')->get();

        return response()->json($data);
    }
}

