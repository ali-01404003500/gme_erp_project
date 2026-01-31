<?php

namespace Modules\HRMS\Services\Settings;

use Modules\HRMS\Models\Settings\NoticeType;

class NoticeTypeService
{
    
    public function getAll(int $limit = 20) {
        return NoticeType::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return NoticeType::create($data);
    }

    public function update(NoticeType $noticeType, array $data)
    {
        $noticeType->update($data);
        return $noticeType;
    }

    public function delete(NoticeType $noticeType)
    {
        $noticeType->delete();
    }

    public function show($id)
    {
        return NoticeType::findOrFail($id);
    }
}
