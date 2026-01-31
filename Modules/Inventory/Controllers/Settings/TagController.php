<?php

namespace Modules\Inventory\Controllers\Settings;

use App\Http\Controllers\Controller;
use Modules\Inventory\Models\Settings\Tag;
use Modules\Inventory\Services\Settings\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{

    /**
     * Service variable
     *
     * @var TagService
     */
    private $service;
    function __construct(TagService $service)
    {
        $this->service = $service;
        $this->middleware('permited');

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['tags'] = $this->service->getAll();

        return view("Inventory::settings.tags.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,NULL,id,deleted_at,NULL',
            'code' => 'required|string|max:255|unique:tags,code,NULL,id,deleted_at,NULL',
        ]);
    
        // Check if an active record with the same code exists
        $existingTag = Tag::onlyTrashed()->where('code', $request->code)->first();

        if ($existingTag) {
            $existingTag->forceDelete();
        }
    
        // Create the new tag
        $this->service->store($validate);
    
        return redirect()->route('inv.settings.tags.index')->with('success', 'Tag created successfully.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['tag'] = $this->service->show($id);

        return view("tags.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        $data['tag'] = $tag;
        //
        return view("tags.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'code' =>'required|string|max:255',

        ]);
        $this->service->update($tag, $validate);

        return redirect()->route('inv.settings.tags.index')->with('success', 'Tag updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $this->service->delete($tag);
        return redirect()->route('inv.settings.tags.index')->with('success', 'Tag deleted successfully.');
    }
}
