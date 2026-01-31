<?php

namespace App\Exports;

use Modules\Inventory\Services\StockService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use function PHPSTORM_META\map;

class stockInHandExport implements FromCollection, WithHeadings
{

    /**
     * Service variable
     *
     * @var StockService
     */
    private $service; 
    function __construct(StockService $service)
    {
        $this->service = $service;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {


        return $this->service->stockInHand()->map(function ($stockInHand) {
            return [
                'name'=>$stockInHand->product->name,
                'total_in'=>$stockInHand->total_in,
                'total_out'=>$stockInHand->total_out,
                'total'=>$stockInHand->stock,
            ]; 
        });
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'in',
            'out',
            'Quantity',
        ];
    }
}
