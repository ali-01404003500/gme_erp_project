<?php

namespace Modules\SalesTarget\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SalesTarget\Models\SalesTargetRateTier;

class SalesIncentiveSlabController extends Controller
{
    public function index()
    {
        $tiers = SalesTargetRateTier::orderBy('min_percent')->get();
        return view('SalesTarget::sales-incentive-slabs.index', compact('tiers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'min_percent' => 'required|numeric|min:0',
            'max_percent' => 'nullable|numeric|gt:min_percent',
            'rate_percent' => 'required|numeric|min:0',
        ]);

        SalesTargetRateTier::create($request->all());
        return back()->with('success', 'Incentive slab যোগ হয়েছে।');
    }

    public function update(Request $request, SalesTargetRateTier $salesIncentiveSlab)
    {
        $request->validate([
            'min_percent' => 'required|numeric|min:0',
            'max_percent' => 'nullable|numeric|gt:min_percent',
            'rate_percent' => 'required|numeric|min:0',
        ]);

        $salesIncentiveSlab->update($request->all());
        return back()->with('success', 'Incentive slab আপডেট হয়েছে।');
    }

    public function destroy(SalesTargetRateTier $salesIncentiveSlab)
    {
        $salesIncentiveSlab->delete();
        return back()->with('success', 'Incentive slab মুছে ফেলা হয়েছে।');
    }
}