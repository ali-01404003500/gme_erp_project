<?php

namespace Modules\Sales\Controllers;


use App\Http\Controllers\Controller;
use Modules\Sales\Models\Courier;
use Modules\Sales\Services\CourierService;
use Illuminate\Http\Request;

class CourierController extends Controller
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
        $this->middleware('permited');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['couriers'] = $this->service->getAll();

        return view("Sales::courier.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Sales::courier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
           'courier_name' => 'required',
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
        return redirect()->route('sales.couriers.edit', $courier->id)->with('success', 'Courier created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['courier'] = $this->service->show($id);

        return view("Sales::courier.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Courier $courier)
    {
        $data['courier'] = $courier;
        return view("Sales::courier.edit", $data);
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

        return redirect()->route('sales.couriers.edit', $courier->id)->with('success', 'Courier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Courier $courier)
    {
        $this->service->delete($courier);
        return redirect()->route('sales.couriers.index')->with('success', 'Courier deleted successfully.');
    }
}
