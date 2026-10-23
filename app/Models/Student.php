<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id_number',
        'full_name',
        'gender',
        'email',
        'phone',
        'course',
        'semester',
        'guardian_name',
        'guardian_phone',
        'address',
        'blood_group',
    ];

    protected $casts = [
        'semester' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(RoomAllocation::class)->orderBy('created_at', 'desc');
    }

    public function activeAllocation(): HasOne
    {
        return $this->hasOne(RoomAllocation::class)->where('status', 'active');
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class)->orderBy('created_at', 'desc');
    }
}
