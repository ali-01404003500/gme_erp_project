<?php

namespace Modules\HRMS\Services\Settings;
use Modules\HRMS\Models\Settings\Supervisor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class SupervisorService
{
     protected $supervisor;

    public function __construct(Supervisor $supervisor)
    {
        $this->supervisor = $supervisor;
    }

    public function getAllSupervisors()
    {
        return $this->supervisor->active()->ordered()->get();
    }

    public function getSupervisorById($id)
    {
        return $this->supervisor->findOrFail($id);
    }

    public function createSupervisor(array $data)
    {
        try {
            DB::beginTransaction();
            
            // Check if hierarchy level is available
            $existingLevel = $this->supervisor->where('hierarchy_level', $data['hierarchy_level'])->first();
            if ($existingLevel) {
                // Shift all higher levels
                $this->supervisor->where('hierarchy_level', '>=', $data['hierarchy_level'])
                    ->increment('hierarchy_level');
            }
            
            $data['created_by'] = auth()->id();
            $supervisor = $this->supervisor->create($data);
            
            DB::commit();
            
            return $supervisor;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Supervisor creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateSupervisor($id, array $data)
    {
        try {
            DB::beginTransaction();
            
            $supervisor = $this->getSupervisorById($id);
            
            // Handle hierarchy level change
            if ($data['hierarchy_level'] != $supervisor->hierarchy_level) {
                // Remove from old position
                $this->supervisor->where('hierarchy_level', '>', $supervisor->hierarchy_level)
                    ->decrement('hierarchy_level');
                
                // Insert at new position
                $this->supervisor->where('hierarchy_level', '>=', $data['hierarchy_level'])
                    ->increment('hierarchy_level');
            }
            
            $data['updated_by'] = auth()->id();
            $supervisor->update($data);
            
            DB::commit();
            
            return $supervisor;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Supervisor update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteSupervisor($id)
    {
        try {
            DB::beginTransaction();
            
            $supervisor = $this->getSupervisorById($id);
            
            // Reorder remaining supervisors
            $this->supervisor->where('hierarchy_level', '>', $supervisor->hierarchy_level)
                ->decrement('hierarchy_level');
            
            $supervisor->delete();
            
            DB::commit();
            
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Supervisor deletion failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function searchEmployees($searchTerm)
    {
        // This could be integrated with your employee database
        // For now, returning dummy data
        return [
            ['code' => 'GME760', 'name' => 'Nafis Ul Haque', 'designation' => 'Senior Manager'],
            ['code' => 'GME746', 'name' => 'Mahamud Hosen', 'designation' => 'Manager'],
            ['code' => 'GME755', 'name' => 'Rafiq Ahmed', 'designation' => 'Deputy Manager'],
        ];
    }

}
