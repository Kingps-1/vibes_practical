<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'student_id',
        'allocated_by',
        'bed_number',
        'allocation_date',
        'vacate_date',
        'status',
        'fee_status',
        'remarks',
    ];

    protected $casts = [
        'allocation_date' => 'date',
        'vacate_date' => 'date',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function allocatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }
}
