<?php

namespace Modules\SalesTarget\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SalesTarget\Models\SalesTargetSlab;

class SalesTargetSlabController extends Controller
{
    public function index()
    {
        $slabs = SalesTargetSlab::latest()->get();
        return view('sales-target-slabs.index', compact('slabs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'min_salary' => 'required|numeric|min:0',
            'max_salary' => 'required|numeric|gt:min_salary',
            'target_multiplier' => 'required|numeric|min:0',
        ]);

        SalesTargetSlab::create($request->all());
        return back()->with('success', 'Slab যোগ হয়েছে।');
    }

    public function update(Request $request, SalesTargetSlab $salesTargetSlab)
    {
        $request->validate([
            'name' => 'nullable|string',
            'min_salary' => 'required|numeric|min:0',
            'max_salary' => 'required|numeric|gt:min_salary',
            'target_multiplier' => 'required|numeric|min:0',
        ]);

        $salesTargetSlab->update($request->all());
        return back()->with('success', 'Slab আপডেট হয়েছে।');
    }

    public function destroy(SalesTargetSlab $salesTargetSlab)
    {
        $salesTargetSlab->delete();
        return back()->with('success', 'Slab মুছে ফেলা হয়েছে।');
    }
}