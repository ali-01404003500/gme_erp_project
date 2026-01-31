<?php

namespace Modules\HRMS\Controllers\Settings;
use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Settings\NoticeType;
use Modules\HRMS\Services\Settings\NoticeTypeService;
use Illuminate\Http\Request;

class NoticeTypeController extends Controller
{

    /**
     * Service variable
     *
     * @var NoticeTypeService
     */
    private $service; 
    function __construct(NoticeTypeService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['noticeTypes'] = $this->service->getAll();

        return view("HRMS::settings.notice-types.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('noticeTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([

            'name' => 'required|string|max:255',
            'code' =>'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);
        $this->service->store($validate);
        return redirect()->route('hrm.settings.notice-types.index')->with('success', 'NoticeType created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['noticeTypes'] = $this->service->show($id);

        return view("noticeTypes.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NoticeType $noticeType)
    {
        $data['noticeType'] = $noticeType;
        //
        return view("noticeTypes.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NoticeType $noticeType)
    {
        $validate = $request->validate([

            'name' => 'required|string|max:255',
            'code' =>'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);
        $this->service->update($noticeType, $validate);

        return redirect()->route('hrm.settings.notice-types.index')->with('success', 'NoticeType updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NoticeType $noticeType)
    {
        $this->service->delete($noticeType);
        return redirect()->route('hrm.settings.notice-types.index')->with('success', 'NoticeType deleted successfully.');
    }
}
