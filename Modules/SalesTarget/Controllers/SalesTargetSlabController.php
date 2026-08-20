<?php

namespace Modules\SalesTarget\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\SalesTarget\Models\SalesTargetSlab;

class SalesTargetSlabController extends Controller
{
    public function index()
    {
        $slabs = SalesTargetSlab::orderByDesc('is_active')->latest()->get();
        return view('SalesTarget::sales-target-slabs.index', compact('slabs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('sales_target_slabs', 'name')
                    ->where(function ($query) use ($request) {
                        return $query->where('is_active', 1);
                    }),
            ],
            'min_salary' => 'required|numeric|min:0',
            'max_salary' => 'required|numeric|gt:min_salary',
            'target_multiplier' => 'required|numeric|min:0',
        ]);

        SalesTargetSlab::create($request->all());
        return back()->with('success', 'The slab has been added.');
    }

    public function update(Request $request, SalesTargetSlab $salesTargetSlab)
    {
       $request->validate([
            'name' => [
                'nullable',
                'string',
                Rule::unique('sales_target_slabs', 'name')
                    ->ignore($salesTargetSlab->id)
                    ->where(function ($query) {
                        $query->where('is_active', 1);
                    }),
            ],
            'min_salary' => 'required|numeric|min:0',
            'max_salary' => 'required|numeric|gt:min_salary',
            'target_multiplier' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $salesTargetSlab->update($request->all());
        return back()->with('success', 'The slab has been updated.');
    }

    public function destroy(SalesTargetSlab $salesTargetSlab)
    { 
        $salesTargetSlab->delete();
        return back()->with('success', 'The slab has been deleted.');
    }
}