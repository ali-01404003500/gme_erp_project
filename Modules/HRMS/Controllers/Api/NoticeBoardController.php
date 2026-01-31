<?php

namespace Modules\HRMS\Controllers\Api;

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
        $this->middleware(middleware: 'permited')->except(methods: ['noticeType']);

    }
    
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        try{
        $data['noticeBoards'] = $this->service->getAll();;

        return response()->json([
                'data' => $data,
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
            ]);
        }
    }

     public function noticeType()
    {
        try{
        $data['noticeTypes'] = NoticeType::all();

        return response()->json([
                'data' => $data,
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
            ]);
        }
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
        $result = $this->service->store($validate);
        return response()->json([
            'data' => $result,
            'status' => true,
            'message' => 'Notice Board created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        try{
        $data['noticeBoard'] = $this->service->show($id);

        return response()->json([
            'data' => $data,
            'status' => true,
        ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
            ]);
        }
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
        $result = $this->service->update($noticeBoard, $validate);

        return response()->json([
            'data' => $result,
            'status' => true,
            'message' => 'Notice Board updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $noticeBoard = $this->service->show($id);
            $this->service->delete($noticeBoard);
            return response()->json([
                'data' => [],
                'status' => true,
                'message' => 'Notice Board deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'status' => false,
                'message' => 'An error occurred while deleting the Notice Board.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
