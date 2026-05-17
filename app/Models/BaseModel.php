<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model{

    /*protected static function booted()
    {
        static::addGlobalScope('latest', function ($query) {
            $query->orderBy('created_at', 'desc');
        });
    }*/

    protected static function booted()
    {
        static::addGlobalScope('latest', function ($query) {
            $model = $query->getModel();
            $table = $model->getTable();

            // only apply when NO joins
            if (count($query->getQuery()->joins ?? []) == 0) {
                $query->orderBy("$table.created_at", 'desc');
            }
        });
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeSearchByFields($query, $filed_names)
    {
        foreach ($filed_names as $key => $filed_name) {

            $query->when(request()->filled($filed_name), function($qr) use($filed_name) {
                $qr->where($filed_name, request()->$filed_name);
             });
        }

    }

    public function scopeUserBranch($query){
        return hasPermission('supper_admin')? $query :  $query->with('createdBy.branch')->whereHas('createdBy.branch', function($q){
            $q->where('id', auth()->user()->branch_id);
        });
    }

    public function scopeLikeSearch($query, $filed_name)
    {
        $query->when(request()->filled($filed_name), function ($qr) use ($filed_name) {
            $qr->where($filed_name, 'like', '%' . request()->$filed_name . '%');
        });
    }
    
    public function scopeSearchLikes($query, $filed_names)
    {
        foreach ($filed_names as $key => $filed_name) {
            $query->when(request()->filled($filed_name), function($qr) use($filed_name) {
                $qr->where($filed_name, 'like', request()->$filed_name. '%');
            });
        }
    }

    public function scopeFilterByDateRange($query, $filed_name, $filter="from_to")
    {
        $query->when(request()->filled($filter), function ($qr) use ($filed_name, $filter) {
            $dataRange = explode(' to ', request()->$filter);
            $from = $dataRange[0];
            if (isset($dataRange[1])) {
                $to = $dataRange[1];
            } else {
                $to = date('Y-m-d', strtotime($from . ' +1 days'));
            }
            $qr->whereBetween($filed_name, [$from, $to]);
        });
    }

    public function scopeActiveCustomers($query)
    {
        return $query->where('status', 2);
    }

    public function scopeActiveBrokers($query)
    {
        return $query->where('status', 2);
    }


    
    public function scopeBranchOnly($query){
        return $query->whereHas('createdBy', function($query) {
            $query->where('branch_id', auth()->user()->branch_id);
        });
    }
}