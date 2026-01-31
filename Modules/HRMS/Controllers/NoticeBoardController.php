<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\HRMS\Models\NoticeBoard;
use Modules\HRMS\Models\Settings\NoticeType;
use Modules\HRMS\Services\NoticeBoardService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
class NoticeBoardController extends Controller
{

    /**
     * Service variable
     *
     * @var NoticeBoardService
     */
    private $service; 
    function __construct(NoticeBoardService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $data['noticeBoards'] = $this->service->getAll();
        $data['noticeTypes'] = NoticeType::all();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('HRMS::noticeboards.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('noticeboard_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }


        return view("HRMS::noticeboards.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()

    {

        $data['noticeTypes'] = NoticeType::all();

        return view('HRMS::noticeboards.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required|string',
            'notice_type_id' => 'required|exists:notice_types,id',
            'publish_date' => 'required|date',
            'publish_time' => 'required|date_format:H:i',
            'expire_date' => 'required|date',
            'description' => 'required|string',
            'status' => 'required|string',
        ]);
        $this->service->store($validate);
        return redirect()->route('hrm.noticeboards.index')->with('success', 'Notice Board created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['noticeBoard'] = $this->service->show($id);

        return view("HRMS::noticeboards.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['noticeBoard'] = $this->service->show($id);
        $data['noticeTypes'] = NoticeType::all();
        //
        return view("HRMS::noticeboards.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $noticeBoard = $this->service->show($id);
        $validate = $request->validate([
            'title' => 'required|string',
            'notice_type_id' => 'required|exists:notice_types,id',
            'publish_date' => 'required|date',
            'publish_time' => 'required|date_format:H:i',
            'expire_date' => 'required|date',
            'description' => 'required|string',
            'status' => 'required|string',
        ]);
        $this->service->update($noticeBoard, $validate);

        return redirect()->route('hrm.noticeboards.index')->with('success', 'Notice Board updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $noticeBoard = $this->service->show($id);
        $this->service->delete($noticeBoard);
        return redirect()->route('hrm.noticeboards.index')->with('success', 'Notice Board deleted successfully.');
    }
}
