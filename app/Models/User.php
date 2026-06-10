<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * ============================================================================
 * User Model
 * ============================================================================
 *
 * Core authentication and identity entity for the activity tracking platform.
 *
 * Responsibilities:
 * --------------------------------------------------------------------------
 * - authentication
 * - authorization
 * - activity ownership
 * - update submissions
 * - personnel/admin role management
 *
 * Database Table:
 * --------------------------------------------------------------------------
 * users
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $department
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Activity> $createdActivities
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ActivityUpdate> $activityUpdates
 *
 * Security Features:
 * --------------------------------------------------------------------------
 * - Password hashing via Laravel cast system
 * - Hidden authentication fields
 * - Session-compatible authentication model
 *
 * Role System:
 * --------------------------------------------------------------------------
 * Supported roles:
 * - personnel
 * - admin
 * ============================================================================
 */
class User extends Authenticatable
{
    /**
     * @use HasFactory<UserFactory>
     */
    use HasFactory, Notifiable;

    /**
     * ----------------------------------------------------------------------
     * Mass Assignable Attributes
     * ----------------------------------------------------------------------
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'department',
        'role',
    ];

    /**
     * ----------------------------------------------------------------------
     * Hidden Attributes
     * ----------------------------------------------------------------------
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * ----------------------------------------------------------------------
     * Attribute Casting
     * ----------------------------------------------------------------------
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * ----------------------------------------------------------------------
     * Created Activities Relationship
     * ----------------------------------------------------------------------
     *
     * Activities authored/created by this user.
     */
    public function createdActivities(): HasMany
    {
        return $this->hasMany(
            Activity::class,
            'created_by'
        );
    }

    /**
     * ----------------------------------------------------------------------
     * Activity Updates Relationship
     * ----------------------------------------------------------------------
     *
     * Updates submitted by this user.
     */
    public function activityUpdates(): HasMany
    {
        return $this->hasMany(
            ActivityUpdate::class
        );
    }

    /**
     * ----------------------------------------------------------------------
     * Role Helper: Administrator Check
     * ----------------------------------------------------------------------
     *
     * Determines whether the user has administrative privileges.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
