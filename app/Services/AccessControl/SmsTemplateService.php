<?php

namespace App\Services\AccessControl;

use App\Models\AccessControl\SmsTemplate;

class SmsTemplateService
{
    
    public function getAll(int $limit = 20) {
        return SmsTemplate::query()
        ->searchByFields(['template_name', 'service_name_id','trigger_name_id'])
        ->searchLikes(['template_title'])
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        return SmsTemplate::create($data);
    }

    public function update(SmsTemplate $smsTemplate, array $data)
    {
        $smsTemplate->update($data);
        return $smsTemplate;
    }

    public function delete(SmsTemplate $smsTemplate)
    {
        $smsTemplate->delete();
    }

    public function show($id)
    {
        return SmsTemplate::findOrFail($id);
    }
}
