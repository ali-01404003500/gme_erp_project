<?php

namespace Modules\HRMS\Models\Kpi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScoreWiseSuggestion extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;   
    
    protected $guarded = [];

    /**
     * Get suggestion based on aggregate score
     */
    public static function getSuggestionByScore($score)
    {
        return self::where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->first();
    }

    /**
     * Generate formatted remarks string
     */
    public function getFormattedRemarks()
    {
        $parts = [];
        
        if ($this->rating_grade) {
            $parts[] = "Grade: {$this->rating_grade}";
        }
        
    
        
        if ($this->training_need) {
            $parts[] = "Suggested Training: {$this->training_need}";
        }

        return implode('. ', $parts) . '.';
    }
}
