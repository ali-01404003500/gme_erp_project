<?php

namespace App\Http\Controllers;

use App\Models\UserLogHistory;
use App\Services\UserLogHistoryService;
use Illuminate\Http\Request;

class UserLogHistoryController extends Controller
{

    /**
     * Service variable
     *
     * @var UserLogHistoryService
     */
    private $service; 
    function __construct(UserLogHistoryService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['userLogHistories'] = $this->service->getAll();
        // dd($data['userLogHistories']);

        return view("user-log-histories.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user-log-histories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->store($validate);
        return redirect()->route('history.user-log-histories.index')->with('success', 'UserLogHistory created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['userLogHistory'] = $this->service->show($id);

        return view("user-log-histories.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserLogHistory $userLogHistory)
    {
        $data['userLogHistory'] = $userLogHistory;
        //
        return view("user-log-histories.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserLogHistory $userLogHistory)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($userLogHistory, $validate);

        return redirect()->route('history.user-log-histories.index')->with('success', 'UserLogHistory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserLogHistory $userLogHistory)
    {
        $this->service->delete($userLogHistory);
        return redirect()->route('history.user-log-histories.index')->with('success', 'UserLogHistory deleted successfully.');
    }
}
