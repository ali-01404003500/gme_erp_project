<?php

namespace Modules\Services\Controllers\Settings;

use App\Http\Controllers\Controller;
use Modules\Services\Models\Settings\ProblemType;
use Modules\Services\Services\Settings\ProblemTypeService;
use Illuminate\Http\Request;

class ProblemTypeController extends Controller
{

    /**
     * Service variable
     *
     * @var ProblemTypeService
     */
    private $service; 
    function __construct(ProblemTypeService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['problemTypes'] = $this->service->getAll();

        return view("problemTypes.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('problemTypes.create');
    }

    public function search(Request $request)
    {
        $search = $request->query('q');
        $types = ProblemType::where('name', 'like', "%{$search}%")
            ->select('name as value', 'name as text')
            ->limit(10)
            ->get();

        return response()->json($types);
    }

  public function store(Request $request)
    {
        $data = $request->json()->all(); // Use this instead of $request->input()

        $validate = $request->validate([
            'name' => 'required|unique:problem_types,name,NULL,id,deleted_at,NULL',
        ]);

        $type = ProblemType::create([
            'name' => $validate['name']
        ]);

        return response()->json([
            'value' => $type->name,
            'text' => $type->name
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['problemType'] = $this->service->show($id);

        return view("problemTypes.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProblemType $problemType)
    {
        $data['problemType'] = $problemType;
        //
        return view("problemTypes.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProblemType $problemType)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($problemType, $validate);

        return redirect()->route('problemTypes.index')->with('success', 'ProblemType updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProblemType $problemType)
    {
        $this->service->delete($problemType);
        return redirect()->route('problemTypes.index')->with('success', 'ProblemType deleted successfully.');
    }
}
