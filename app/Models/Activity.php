<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * ============================================================================
 * Activity Model
 * ============================================================================
 *
 * Represents an official activity, assignment, task, or operational event
 * tracked within the system.
 *
 * Examples:
 * --------------------------------------------------------------------------
 * - Department meeting
 * - Academic workshop
 * - Maintenance assignment
 * - Administrative review
 * - Staff operation
 *
 * Database Table:
 * --------------------------------------------------------------------------
 * activities
 *
 * Relationships:
 * --------------------------------------------------------------------------
 * - Belongs to a creator (User)
 * - Has many status updates
 *
 * Lifecycle:
 * --------------------------------------------------------------------------
 * Activity
 *   → updates submitted by personnel
 *   → tracked via ActivityUpdate records
 * ============================================================================
 */
class Activity extends Model
{
    /**
     * ----------------------------------------------------------------------
     * Mass Assignable Attributes
     * ----------------------------------------------------------------------
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'activity_date',
        'created_by',
    ];

    /**
     * ----------------------------------------------------------------------
     * Attribute Casting
     * ----------------------------------------------------------------------
     *
     * Automatically converts database values into native PHP types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'activity_date' => 'date',
    ];

    /**
     * ----------------------------------------------------------------------
     * Creator Relationship
     * ----------------------------------------------------------------------
     *
     * The administrator or personnel member who created the activity.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /**
     * ----------------------------------------------------------------------
     * Activity Updates Relationship
     * ----------------------------------------------------------------------
     *
     * All progress/status updates associated with this activity.
     */
    public function updates(): HasMany
    {
        return $this->hasMany(
            ActivityUpdate::class
        );
    }
}
