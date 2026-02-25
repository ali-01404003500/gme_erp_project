<?php

namespace Modules\SalesTarget\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\SalesTarget\Services\IncentiveSettingsService;

class SalesIncentiveController extends Controller
{
    public function index()
    {
        // Using subqueries with updated table names to avoid GROUP BY issues
        $incentives = DB::table('sales_incentives_settings as si')
            ->select('si.*')
            ->addSelect([
                'slabs_count' => DB::table('sales_incentive_slabs_settings')
                    ->whereColumn('sales_incentive_id', 'si.id')
                    ->selectRaw('count(*)'),
                'min_reach' => DB::table('sales_incentive_slabs_settings')
                    ->whereColumn('sales_incentive_id', 'si.id')
                    ->selectRaw('IFNULL(min(min_range), 0)'),
                'max_reach' => DB::table('sales_incentive_slabs_settings')
                    ->whereColumn('sales_incentive_id', 'si.id')
                    ->selectRaw('IFNULL(max(max_range), 0)'),
            ])
            ->orderBy('si.year', 'desc')
            ->get();

        return view('SalesTarget::salesIncentives.incentives.index', compact('incentives'));
    }

    public function create()
    {
        return view('SalesTarget::salesIncentives.incentives.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'year'  => 'required',
            'slabs' => 'required|array|min:1'
        ]);

        try {
            IncentiveSettingsService::storeIncentiveSettings($request->all());
            return redirect()->route('sales_target.settings.incentives.index')
                ->with('success', 'Incentive Setup created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Creation Failed: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $incentive = DB::table('sales_incentives_settings')->where('id', $id)->first();
        $slabs = DB::table('sales_incentive_slabs_settings')->where('sales_incentive_id', $id)->get();

        return view('SalesTarget::salesIncentives.incentives.edit', compact('incentive', 'slabs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title' => 'required', 'year' => 'required', 'slabs' => 'required|array']);

        try {
            IncentiveSettingsService::updateIncentiveSettings($id, $request->all());
            return redirect()->route('sales_target.settings.incentives.index')
                ->with('success', 'Updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // Delete child slabs first to maintain integrity
                DB::table('sales_incentive_slabs_settings')
                    ->where('sales_incentive_id', $id)
                    ->delete();
                
                // Delete master record
                DB::table('sales_incentives_settings')
                    ->where('id', $id)
                    ->delete();
            });

            return redirect()->route('sales_target.settings.incentives.index')
                ->with('success', 'Incentive setup deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $incentive = DB::table('sales_incentives_settings')->where('id', $id)->first();

        if (!$incentive) {
            return redirect()->route('sales_target.settings.incentives.index')
                ->with('error', 'Incentive setup not found.');
        }

        $slabs = DB::table('sales_incentive_slabs_settings')
            ->where('sales_incentive_id', $id)
            ->orderBy('min_range', 'asc')
            ->get();

        return view('SalesTarget::salesIncentives.incentives.show', compact('incentive', 'slabs'));
    }
}