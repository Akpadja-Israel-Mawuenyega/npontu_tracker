<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ============================================================================
 * ActivityUpdate Model
 * ============================================================================
 *
 * Represents a progress update or completion report submitted against an
 * activity.
 *
 * @property int $id
 * @property int $activity_id
 * @property int $user_id
 * @property string $status
 * @property string|null $remark
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read Activity $activity
 * @property-read User $user
 * ============================================================================
 */
class ActivityUpdate extends Model
{
    /**
     * ----------------------------------------------------------------------
     * Mass Assignable Attributes
     * ----------------------------------------------------------------------
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'activity_id',
        'user_id',
        'status',
        'remark',
    ];

    /**
     * ----------------------------------------------------------------------
     * Activity Relationship
     * ----------------------------------------------------------------------
     *
     * The parent activity this update belongs to.
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(
            Activity::class
        );
    }

    /**
     * ----------------------------------------------------------------------
     * User Relationship
     * ----------------------------------------------------------------------
     *
     * The personnel member who submitted the update.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }

    /**
     * ----------------------------------------------------------------------
     * Completion Helper
     * ----------------------------------------------------------------------
     *
     * Determines whether the activity update is marked as completed.
     */
    public function isDone(): bool
    {
        return $this->status === 'done';
    }
}
