<?php

namespace App\Http\Controllers\AccessControl;
use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use App\Models\AccessControl\BranchType;
use App\Models\AccessControl\CompanyInfo;
use App\Services\AccessControl\BranchService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class BranchController extends Controller
{

    /**
     * Service variable
     *
     * @var BranchService
     */
    private $service; 
    function __construct(BranchService $service)
    {
        $this->service = $service;
        $this->middleware('permited');

    }
    
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $data['branchs'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Inventory::branchs.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('branch_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("access_control.branchs.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['branchTypes'] = BranchType::all();
        return view('access_control.branchs.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            //validate rules
            'branch_type_id' => 'required|exists:branch_types,id',
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'contact_no' => ['required', 'max:255', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:20480',
        ]);
        $branches = $request->validate([
            'names' => "array",
            'names.*' => "nullable|string",
            'mobiles' => "required|array",
            'mobiles.*' => ["required", "string", "regex:/^([0-9\s\-\+\(\)]*)$/"],
            'designations' => "required|array",
            'designations.*' => "required|string",
        ]);
        $result = $this->service->store($validate,  $branches );
        return redirect()->route('access_control.branchs.edit', $result['branch']->id)->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['branch'] = $this->service->show($id);

        return view("access_control.branchs.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        $data['branch'] = $branch;
        $data['branchTypes'] = BranchType::all();
        //
        return view("access_control.branchs.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $validate = $request->validate([
            //validate rules
            'branch_type_id' => 'required|exists:branch_types,id',
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'contact_no' =>  ['required', 'max:255', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:20480',
        ]);
        $branches = $request->validate([
            'names' => "array",
            'names.*' => "nullable|string",
            'mobiles' => "required|array",
            'mobiles.*' => ["required", "string", "regex:/^([0-9\s\-\+\(\)]*)$/"],
            'designations' => "required|array",
            'designations.*' => "required|string",
        ]);
        $result = $this->service->update($branch, $validate,  $branches );

        return redirect()->route('access_control.branchs.edit', $branch->id)->with('success', 'Branch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $this->service->delete($branch);
        return redirect()->route('access_control.branchs.index')->with('success', 'Branch deleted successfully.');
    }
}
