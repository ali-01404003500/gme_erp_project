<?php

namespace Modules\HRMS\Services;

use Modules\HRMS\Models\NoticeBoard;
use Carbon\Carbon;

class NoticeBoardService
{
    
    public function getAll(int $limit = 20) {
        $request = request();

        return NoticeBoard::query()
            ->with(['noticeType'])
            ->when($request->filled('title'), function($query) use ($request) {
                $query->where('title', 'like', '%'.$request->get('title').'%');
            })

            ->when($request->filled('notice_type_id'), function($query) use ($request) {
                $query->where('notice_type_id', $request->get('notice_type_id'));
            })
            ->when($request->filled('fromDate') && $request->filled('toDate'), function ($query) use ($request) {
                $query->where('publish_date', ">=",$request->fromDate )->where('publish_date', "<=",$request->toDate );
            })
            ->paginate($limit);


    }
    
    public function store(array $data)
    {
        return NoticeBoard::create($data);
    }

    public function update(NoticeBoard $noticeBoard, array $data)
    {
        $noticeBoard->update($data);
        return $noticeBoard;
    }

    public function delete(NoticeBoard $noticeBoard)
    {
        $noticeBoard->delete();
    }

    public function show($id)
    {
        return NoticeBoard::findOrFail($id);
    }
}
