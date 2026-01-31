<?php

namespace Modules\HRMS\Services;

use Modules\HRMS\Models\AttendanceReport;

class AttendanceReportService
{
    
    public function getAll(int $limit = 20) {
        return AttendanceReport::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return AttendanceReport::create($data);
    }

    public function update(AttendanceReport $attendanceReport, array $data)
    {
        $attendanceReport->update($data);
        return $attendanceReport;
    }

    public function delete(AttendanceReport $attendanceReport)
    {
        $attendanceReport->delete();
    }

    public function show($id)
    {
        return AttendanceReport::findOrFail($id);
    }
}
