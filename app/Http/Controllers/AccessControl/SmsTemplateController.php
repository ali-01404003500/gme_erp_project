<?php

namespace App\Http\Controllers\AccessControl;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\ServiceName;
use App\Models\AccessControl\SmsTemplate;
use App\Models\AccessControl\TriggerName;
use App\Services\AccessControl\SmsTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SmsTemplateController extends Controller
{

    /**
     * Service variable
     *
     * @var SmsTemplateService
     */
    private $service; 
    function __construct(SmsTemplateService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['smsTemplates'] = $this->service->getAll();

        $data['serviceNames'] = ServiceName::query()->where('status',1)->get();
        $data['triggerNames'] = TriggerName::query()->where('status',1)->get();
        return view("access_control.sms.templates.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['serviceNames'] = ServiceName::query()->where('status',1)->get();
        $data['triggerNames'] = TriggerName::query()->where('status',1)->get();
        $lastCodeName = SmsTemplate::orderByDesc('id')->value('code_name');

        if ($lastCodeName) {
            // Example: TEM01 -> TEM02
            preg_match('/(\d+)$/', $lastCodeName, $matches);

            if (!empty($matches)) {
                $number = (int) $matches[1] + 1;
                $prefix = substr($lastCodeName, 0, -strlen($matches[1]));

                $codeName = $prefix . str_pad(
                    $number,
                    strlen($matches[1]),
                    '0',
                    STR_PAD_LEFT
                );
            } else {
                $codeName = $lastCodeName . '1';
            }
        } else {
            $codeName = 'TEM01';
        }
        $data['codeName'] = $codeName;
        return view('access_control.sms.templates.create', $data);
    }
    public function serviceNameWiseTrigerName(Request $request){
        $triggerNames = TriggerName::where('service_name_id', $request->input('service_name_id'))->get();
        return response()->json(['triggerNames' => $triggerNames]);
    }
  
    // In your controller (e.g. SmsController.php)

    public function loadEntities(Request $request)
    {
        $tableName = $request->input('table_name');
    
        // Get the column names from the specified table
        $columns = Schema::getColumnListing($tableName);
    
        // Return the column names as JSON
        return response()->json($columns);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'code_name' => 'required|string|max:10',
            'template_title' => 'required|string|max:255',
            'service_name_id' => 'required|exists:service_names,id',
            'trigger_name_id' => 'required|exists:trigger_names,id',
            'template_body' => 'required|string',
        ]);
        $this->service->store($validate);
        return redirect()->route('sms.templates.index')->with('success', 'SmsTemplate created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['smsTemplate'] = $this->service->show($id);

        return view("smsTemplates.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $smsTemplate = $this->service->show($id);
        $data['smsTemplate'] = $smsTemplate;
        $data['serviceNames'] = ServiceName::query()->where('status',1)->get();
        $data['triggerNames'] = TriggerName::query()->where('status',1)->get();
        return view("access_control.sms.templates.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $smsTemplate = $this->service->show($id);
        $validate = $request->validate([
            'template_title' => 'required|string|max:255',
            'service_name_id' => 'required|exists:service_names,id',
            'trigger_name_id' => 'required|exists:trigger_names,id',
            'template_body' => 'required|string',
        ]);
        $this->service->update($smsTemplate, $validate);

        return redirect()->route('sms.templates.index')->with('success', 'SmsTemplate updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $smsTemplate = $this->service->show($id);
        $this->service->delete($smsTemplate);
        return redirect()->route('sms.templates.index')->with('success', 'SmsTemplate deleted successfully.');
    }
}
