<?php

namespace Modules\Services\Controllers;


use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Settings\Department;
use Modules\Services\Models\EngineerAssign;
use Modules\Services\Models\Service;
use Modules\Services\Models\ServiceAssign;
use Modules\Services\Models\ServiceToken;
use Modules\Services\Services\ServiceAssignService;
use Illuminate\Http\Request;
use Modules\Services\Models\Settings\ServiceType;

class ServiceAssignController extends Controller
{

    /**
     * Service variable
     *
     * @var ServiceAssignService
     */
    private $service; 
    function __construct(ServiceAssignService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['serviceTokens'] = ServiceToken::whereNotIn('action', ['Live', 'Done'])
            ->paginate(20);

        $department_id = Department::whereIn('name', ['Sales & Service', 'Sales & Marketing'])
            ->pluck('id')
            ->toArray();

        $data['engineers'] = Employee::where('status', 1)->whereHas('employementDetail', function($q) use ($department_id) {
                $q->whereIn('department_id', $department_id);
            })
            ->get();

            

                $data ['serviceTypes'] = ServiceType::all();


        return view("Services::service-assign.index", $data);
    }

    public function getTokenDetails($id)
    {
        $token = ServiceToken::with('service', 'customer')->findOrFail($id);

        return response()->json([
            'id' => $token->id,
            'token_no' => $token->service->service_unique_id?? '',
            'token_date' => $token->token_date,
            'problem_type' => $token->problem_type,
            'problem_details' => $token->problem_details,
            'product' => $token->product->name,
            'serial_number' => $token->serial_number,
            'customer_name' => $token->customer->company_name,
            'customer_phone' => $token->contact_person_phone ,
            'customer_address' => $token->customer->address,
            'work_type' => $token->work_type,
            'documents' => $token->documents,
            'status' => $token->action,
        ]);
    }

    public function assignEngineer(Request $request)
    {
        // dd($request->all('token_id', 'engineer_id', 'service_date', 'service_priority', 'service_type'));
        $request->validate([
            'token_id' => 'required|exists:service_tokens,id',
            'engineer_id' => 'required|exists:employees,id',
            'service_date' => 'required|date',
            'service_priority' => 'required|string',
            'service_type' => 'required|string',
        ]);

        // Create new EngineerAssign record
        $engineerAssign = EngineerAssign::create([
            'service_id' => ServiceToken::find($request->token_id)->service_id,
            'service_token_id' => $request->token_id,
            'service_date' => $request->service_date,
            'service_priority' => $request->service_priority,
            'service_type' => $request->service_type,
        ]);
        $engineerAssign->engineers()->attach($request->engineer_id);

        $serviceToken = ServiceToken::find($request->token_id);
        $serviceToken->update([
            'action' => 'Live',
        ]);
        $serviceToken->service->update([
            'action'=> 'Live',
        ]);


        return response()->json(['message' => 'Engineer assigned successfully.']);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('serviceAssigns.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'emergency_note' => 'required|string|max:255',
            'service_assign_id' => 'required|exists:service_assigns,id',
        ]);
        $this->service->store($validate);
        return redirect()->route('services.service-assign.index', $request->id)->with('success', 'EmergencyNote updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['serviceAssign'] = $this->service->show($id);

        return view("serviceAssigns.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceAssign $serviceAssign)
    {
        $data['serviceAssign'] = $serviceAssign;
        //
        return view("serviceAssigns.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceAssign $serviceAssign)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($serviceAssign, $validate);

        return redirect()->route('serviceAssigns.index')->with('success', 'ServiceAssign updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceAssign $serviceAssign)
    {
        $this->service->delete($serviceAssign);
        return redirect()->route('serviceAssigns.index')->with('success', 'ServiceAssign deleted successfully.');
    }
}
