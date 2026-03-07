<?php

namespace App\Services;

class AutocompleteService
{
    //search( string $model,  array $searchColumns, string $searchValue,  array $displayColumns = ['id', 'name'], int $limit = 10,  array $extraConditions = []
   
    public function customerSearch(
        string $model,
        array $searchColumns,
        string $searchValue,
        array $displayColumns = ['id', 'name'],
        int $limit = 10,
        array $extraConditions = []
    ) {
        if (!$searchValue) {
            return collect([]);
        }

        $query = $model::query();

        // Extra conditions (optional)
        foreach ($extraConditions as $column => $value) {
            $query->where($column, $value);
        }

        // Search logic
        $query->where(function ($q) use ($searchColumns, $searchValue) {
            foreach ($searchColumns as $column) {
                $q->orWhere($column, 'LIKE', "%{$searchValue}%");
            }
        });

        return $query->select($displayColumns)
            ->with('area')
            ->limit($limit)
            ->get() 
            ->map(function ($item) use ($displayColumns) {
                return [
                    'id'    => $item->{$displayColumns[0]},
                    'label' => $item->name, // dropdown display
                    'value' => $item->{$displayColumns[1]}, // input fill
                    'text'  => $item->{$displayColumns[1]}, 

                    'company_name'  => $item->{$displayColumns[1]}, 
                    'area'  => $item->area?->area, 
                    'phone'  => $item->{$displayColumns[3]??'phone'}, 
                    'customer_type'  => $item->{$displayColumns[4]??'customer_type'}, 
                    'address'  => $item->{$displayColumns[5]??'address'}, 
                ];
            });
 
    }


    public function productSearch(
        string $model,
        array $searchColumns,
        string $searchValue,
        array $displayColumns = ['id', 'name'],
        int $limit = 10,
        array $extraConditions = []
    ) {
        if (!$searchValue) {
            return collect([]);
        }

        $query = $model::query();

        // Extra conditions (optional)
        foreach ($extraConditions as $column => $value) {
            $query->where($column, $value);
        }

        // Search logic
        $query->where(function ($q) use ($searchColumns, $searchValue) {
            foreach ($searchColumns as $column) {
                $q->orWhere($column, 'LIKE', "%{$searchValue}%");
            }
        });

        return $query->select($displayColumns)
            ->with('brand')
            ->limit($limit)
            ->get() 
            ->map(function ($item) use ($displayColumns) {
                return [
                    'id'    => $item->{$displayColumns[0]},
                    'label' => $item->name, // dropdown display
                    'value' => $item->{$displayColumns[1]}, // input fill
                    'text'  => $item->{$displayColumns[1]}, 
                ];
            });

             
    }

    
    public function search(
        string $model,
        array $searchColumns,
        string $searchValue,
        array $displayColumns = ['id', 'name'],
        int $limit = 10,
        array $extraConditions = []
    ) {
        if (!$searchValue) {
            return collect([]);
        }

        $query = $model::query();

        // Extra conditions (optional)
        foreach ($extraConditions as $column => $value) {
            $query->where($column, $value);
        }

        // Search logic
        $query->where(function ($q) use ($searchColumns, $searchValue) {
            foreach ($searchColumns as $column) {
                $q->orWhere($column, 'LIKE', "%{$searchValue}%");
            }
        });

        return $query->select($displayColumns) 
            ->limit($limit)
            ->get() 
            ->map(function ($item) use ($displayColumns) {
                return [
                    'id'    => $item->{$displayColumns[0]},
                    'label' => $item->{$displayColumns[1]}, // dropdown display
                    'value' => $item->{$displayColumns[1]}, // input fill
                    'text'  => $item->{$displayColumns[1]}, 
                ];
            });

             
    }
}