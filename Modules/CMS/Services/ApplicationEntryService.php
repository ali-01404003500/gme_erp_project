<?php

namespace Modules\CMS\Services;

use Illuminate\Support\Facades\DB;
use Modules\Account\Models\AdvanceChequeEntryDetail;
use Modules\CMS\Models\ApplicationEntry;
use Illuminate\Support\Str;

class ApplicationEntryService
{
    
    public function getAll(int $limit = 20) 
    {
        // Group entries by batch_id and show only primary entries
        return ApplicationEntry::query()
            ->searchByFields(['customer_id'])
            ->with(['customer', 'advanceChequeEntryDetail', 'createdBy', 'approvedBy', 'handoverBy', 'receivedBy', 'deniedBy'])
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MIN(id)'))
                    ->from('application_entries')
                    ->groupBy(DB::raw('COALESCE(batch_id, id)'));
            })
            ->paginate($limit);
    }
    
    public function store(array $data)
    {
        if ($data['type'] === 'Cheque') {
            // Generate unique batch ID for multiple cheques
            $batchId = 'BATCH_' . time() . '_' . Str::random(6);
            
            // Loop through selected cheque details
            foreach ($data['advance_cheque_entry_detail_id'] as $detailId) {
                ApplicationEntry::create([
                    'batch_id'    => $batchId,
                    'customer_id' => $data['customer_id'][$detailId],
                    'type'        => $data['type'],
                    'date'        => $data['date'],
                    'description' => $data['descriptions'],
                    'advance_cheque_entry_detail_id' => $detailId,
                ]);

                AdvanceChequeEntryDetail::where('id', $detailId)
                    ->update(['status' => 'Returned']);
            }
        } else {
            // Store single Deed Document / NOC entry (no batch_id needed)
            ApplicationEntry::create([
                'customer_id' => $data['customer_id'],
                'type'        => $data['type'],
                'date'        => $data['date'],
                'description' => $data['description'],
            ]);
        }

        return true;
    }

    public function update(ApplicationEntry $applicationEntry, array $data)
    {
        $applicationEntry->update($data);
        return $applicationEntry;
    }

    public function delete(ApplicationEntry $applicationEntry)
    {
        // If this is a batch entry, delete all entries in the batch
        if ($applicationEntry->batch_id) {
            ApplicationEntry::where('batch_id', $applicationEntry->batch_id)->delete();
        } else {
            $applicationEntry->delete();
        }
    }

    public function show($id)
    {
        return ApplicationEntry::findOrFail($id);
    }

    // Update status for entire batch
    public function updateBatchStatus($id, $status, $data = [])
    {
        $applicationEntry = $this->show($id);
        
        if ($applicationEntry->batch_id) {
            // Update all entries in the batch
            ApplicationEntry::where('batch_id', $applicationEntry->batch_id)
                ->update(array_merge(['status' => $status], $data));
        } else {
            // Update single entry
            $applicationEntry->update(array_merge(['status' => $status], $data));
        }
        
        return true;
    }
}