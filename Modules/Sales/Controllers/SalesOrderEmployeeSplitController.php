<?php

namespace Modules\Sales\Controllers;

use Illuminate\Http\Request;
use Modules\Sales\Models\SalesOrder;
use Modules\SalesTarget\Models\SalesOrderEmployeeSplit;
use Modules\SalesTarget\Services\SalesTargetService;

class SalesOrderEmployeeSplitController extends \App\Http\Controllers\Controller
{
    public function show(SalesOrder $order)
    {
        $splits = SalesOrderEmployeeSplit::where('sales_order_id', $order->id)
            ->with('employee:id,full_name')
            ->get();
            
        return response()->json(['splits' => $splits]);
    }

  public function store(Request $request, SalesOrder $order, SalesTargetService $service)
{
    try {
        $request->validate([
            'splits' => 'required|array|min:1',
            'splits.*.employee_id' => 'required|exists:employees,id|distinct',
            'splits.*.percentage' => 'required|numeric|min:0.01|max:100',
        ]);

        $total = collect($request->splits)->sum('percentage');

        if (round($total, 2) != 100) {
            return response()->json([
                'message' => "The total percentage must be exactly 100%. Currently, it is {$total}%.",
            ], 422);
        }

        SalesOrderEmployeeSplit::where('sales_order_id', $order->id)->delete();

        foreach ($request->splits as $split) {
            SalesOrderEmployeeSplit::create([
                'sales_order_id' => $order->id,
                'employee_id' => $split['employee_id'],
                'percentage' => $split['percentage'],
            ]);
        }

        $service->syncOrderAchievement($order);

        return response()->json([
            'message' => 'Split has been saved successfully.'
        ]);

    } catch (\Throwable $e) {

        return response()->json([
            'message' => 'An error occurred while saving the split.',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);
    }
}

    public function destroy(SalesOrder $order, SalesTargetService $service)
    {
        SalesOrderEmployeeSplit::where('sales_order_id', $order->id)->delete();
        $service->syncOrderAchievement($order);

        return response()->json(['message' => 'Split has been removed. It will revert to a single employee.']);
    }
}