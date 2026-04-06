<?php
 
namespace Modules\SalesTarget\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Achievement extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Laravel defaults to 'achievements', so this is optional but good for clarity.
     */
    protected $table = 'achievements';

    /**
     * The primary key associated with the table.
     * This is required because you named it 'achievement_id' instead of 'id'.
     */
    protected $primaryKey = 'achievement_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'invoice_date',
        'invoice_number',
        'invoice_month',
        'invoice_amount',
        'achievement_amount',
        'invoice_type',
        'invoice_collection_amount',
        'invoice_due_amount',
    ];

    /**
     * Get the employee that owns the achievement.
     * Linking this to the User model for reporting.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}