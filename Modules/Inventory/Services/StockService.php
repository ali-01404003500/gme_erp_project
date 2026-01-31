<?php

namespace Modules\Inventory\Services;


use App\Models\AccessControl\Branch;
use Modules\Inventory\Models\Stock;
use Illuminate\Support\Facades\DB;

class StockService
{

    public function getAll(int $limit = 20)
    {
        return Stock::withoutGlobalScope('latest')->where('branch_id', auth()->user()->branch_id ?? 1)
            ->likeSearch('product_id')
            ->paginate($limit);
    }

    private function getUserBranchId()
    {
        // return auth()->user()->branch_id;
        /**
         * TODO: get user branch id from user employee table
         */
        return auth()->user()->branch_id ?? 1;
    }

    public function store(array $data)
    {
        if (!isset($data['branch_id'])) {
            $data['branch_id'] = $this->getUserBranchId();
        }
        return Stock::create($data);
    }

    public function update(Stock $stock, array $data)
    {
        $stock->update($data);
        return $stock;
    }

    public function delete(Stock $stock)
    {
        $stock->delete();
    }

    public function show($id)
    {
        return Stock::findOrFail($id);
    }

    public function countStockByProduct($productId)
    {
        return Stock::withoutGlobalScope('latest')->where('branch_id', auth()->user()->branch_id ?? 1)->where('product_id', $productId)->get()->sum('in_qty') - Stock::withoutGlobalScope('latest')->where('branch_id', auth()->user()->branch_id ?? 1)->where('product_id', $productId)->get()->sum('out_qty');
    }

    public function totalInQtyByProduct($productId)
    {
        return Stock::withoutGlobalScope('latest')->where('branch_id', auth()->user()->branch_id ?? 1)->where('product_id', $productId)->get()->sum('in_qty');
    }

    public function totalOutQtyByProduct($productId)
    {
        return Stock::withoutGlobalScope('latest')->where('branch_id', auth()->user()->branch_id ?? 1)->where('product_id', $productId)->get()->sum('out_qty');
    }

    public function countStockByProductAndBranch($productId, $branchId)
    {
        return Stock::withoutGlobalScope('latest')->where('product_id', $productId)
            ->where('branch_id', $branchId)
            ->get()->sum('in_qty') - Stock::withoutGlobalScope('latest')->where('product_id', $productId)
                ->where('branch_id', $branchId)
                ->get()->sum('out_qty');
    }

    public function countStockByLotNo($lotNo)
    {
        return Stock::withoutGlobalScope('latest')->where('branch_id', auth()->user()->branch_id ?? 1)->where('lot_no', $lotNo)->get()->sum('in_qty') - Stock::withoutGlobalScope('latest')->where('branch_id', auth()->user()->branch_id ?? 1)->where('lot_no', $lotNo)->get()->sum('out_qty');
    }


    public function countStockBySerialNo($serialNo)
    {
        return Stock::withoutGlobalScope('latest')->where('branch_id', auth()->user()->branch_id ?? 1)->where('serial_no', $serialNo)->get()->sum('in_qty') - Stock::withoutGlobalScope('latest')->where('branch_id', auth()->user()->branch_id ?? 1)->where('serial_no', $serialNo)->get()->sum('out_qty');
    }



    public function stockInHand()
    {
        return Stock::withoutGlobalScope('latest')->where('branch_id', auth()->user()->branch_id ?? 1)->groupBy('product_id')->selectRaw('product_id, sum(in_qty) as total_in, sum(out_qty) as total_out, sum(in_qty) - sum(out_qty) as stock')->with('product')->get();
    }


    public function stockInHandByBranch($branchId)
    {
        return Stock::withoutGlobalScope('latest')
            ->where('branch_id', $branchId)
            ->groupBy('product_id')
            ->selectRaw('product_id, sum(in_qty) as total_in, sum(out_qty) as total_out, sum(in_qty) - sum(out_qty) as stock')
            ->with('product')
            ->get();
    }


    // product ledger report

    public function productLedger($productId)
    {
        return Stock::withoutGlobalScope('latest')->where('branch_id', auth()->user()->branch_id ?? 1)
            ->where('product_id', $productId)
            ->with([
                'source' => function ($query) {
                    $query->withTrashed();
                }
            ])
            ->orderBy('date', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return $item->source?->parent_id;   // group by parent so every group need a parent attribute it can be accessor method getParentIdAttribute with return foreign id that correspond table
            });
    }


    public function availableSerialsProductStocks($productId)
    {
        return Stock::withoutGlobalScope('latest')->where('branch_id', auth()->user()->branch_id ?? 1)
            ->where('product_id', $productId)
            ->select(
                "product_id",
                'serial_no',
                DB::raw('sum(in_qty) - sum(out_qty) as stock'),
                DB::raw('(select id from stocks s where s.serial_no = stocks.serial_no and s.product_id = stocks.product_id and s.stock_type = "in" limit 1) as id'),
                DB::raw('(select source_type from stocks s where s.serial_no = stocks.serial_no and s.product_id = stocks.product_id and s.stock_type = "in" limit 1) as source_type'),
                DB::raw('(select source_id from stocks s where s.serial_no = stocks.serial_no and s.product_id = stocks.product_id and s.stock_type = "in" limit 1) as source_id')
            )
            ->groupBy(['serial_no', 'product_id'])
            ->havingRaw('stock > 0')
            ->with(['product', 'source'])
            ->get();
    }


    public function availableSerialsProductStocksWithSerials($productId, $serials)
    {
        return Stock::withoutGlobalScope('latest')->where('branch_id', auth()->user()->branch_id ?? 1)
            ->where('product_id', $productId)
            ->whereIn('serial_no', $serials)
            ->select(
                "product_id",
                "branch_id",
                'serial_no',
                DB::raw('sum(in_qty) - sum(out_qty) as stock'),
                DB::raw('(select id from stocks s where s.serial_no = stocks.serial_no  and s.product_id = stocks.product_id and s.stock_type = "in" limit 1) as id'),
                DB::raw('(select source_type from stocks s where s.serial_no = stocks.serial_no  and s.product_id = stocks.product_id and s.stock_type = "in" limit 1) as source_type'),
                DB::raw('(select source_id from stocks s where s.serial_no = stocks.serial_no  and s.product_id = stocks.product_id and s.stock_type = "in" limit 1) as source_id')
            )
            ->groupBy(['serial_no', 'product_id', 'branch_id'])
            ->havingRaw('stock > 0')
            ->with(['product', 'source'])
            ->get();
    }

    public function availableLotsProductStocks($productId)
    {

        return Stock::withoutGlobalScope('latest')->where('branch_id', auth()->user()->branch_id ?? 1)
            ->where('product_id', $productId)
            ->select(
                "product_id",
                "branch_id",
                'lot_no',
                DB::raw('sum(in_qty) - sum(out_qty) as stock'),
                DB::raw('(select id from stocks s where s.lot_no = stocks.lot_no and s.product_id = stocks.product_id and s.stock_type = "in" limit 1) as id'),
                DB::raw('(select source_type from stocks s where s.lot_no = stocks.lot_no and s.product_id = stocks.product_id and s.stock_type = "in" limit 1) as source_type'),
                DB::raw('(select source_id from stocks s where s.lot_no = stocks.lot_no and s.product_id = stocks.product_id and s.stock_type = "in" limit 1) as source_id')
            )
            ->groupBy(['lot_no', 'product_id', 'branch_id'])
            // ->where('stock', '>', 0)
            ->havingRaw('stock > 0')
            ->with(['product'])
            ->get();
    }

    public function availableLotsProductStocksWithLots($productId, $lots)
    {

        return Stock::withoutGlobalScope('latest')->where('branch_id', auth()->user()->branch_id ?? 1)
            ->where('product_id', $productId)
            ->whereIn('lot_no', $lots)
            ->select(
                "product_id",
                "branch_id",
                'lot_no',
                DB::raw('sum(in_qty) - sum(out_qty) as stock'),
                DB::raw('(select id from stocks s where s.lot_no = stocks.lot_no and s.product_id = stocks.product_id and s.stock_type = "in" limit 1) as id'),
                DB::raw('(select source_type from stocks s where s.lot_no = stocks.lot_no and s.product_id = stocks.product_id and s.stock_type = "in" limit 1) as source_type'),
                DB::raw('(select source_id from stocks s where s.lot_no = stocks.lot_no and s.product_id = stocks.product_id and s.stock_type = "in" limit 1) as source_id')
            )
            ->groupBy(['lot_no', 'product_id', 'branch_id'])
            // ->where('stock', '>', 0)
            ->havingRaw('stock > 0')
            ->with(['product'])
            ->get();
    }
    function getInSource($productId, $serial_no)
    {

        return Stock::withoutGlobalScope('latest')->where('branch_id', auth()->user()->branch_id ?? 1)?->where('product_id', $productId)?->where('serial_no', $serial_no)?->where('stock_type', 'in')?->with('source')?->first()?->source;
    }

}
