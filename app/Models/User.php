<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, LogsActivity;

    protected $fillable = [
        'name',
        'fullname',
        'email',
        'password',
        'avatar',
        'department_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    /**
     * Get the certificates earned by the user.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Get the exam attempts by the user.
     */
    public function examAttempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    /**
     * Get latest attempt for a specific exam
     */
    public function getLatestAttemptForExam($examId)
    {
        return $this->examAttempts()
            ->where('exam_id', $examId)
            ->latest('started_at')
            ->first();
    }

    /**
     * Get the user's avatar URL
     */
    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return null;
        }

        return str_starts_with($this->avatar, 'http')
            ? $this->avatar
            : Storage::url($this->avatar);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "User has been {$eventName}");
    }

    // /**
    //  * User roles relationship
    //  */
    // public function roles(): BelongsToMany
    // {
    //     //return $this->belongsToMany(Role::class);
    // }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
