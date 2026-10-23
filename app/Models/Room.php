<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'block',
        'room_type',
        'capacity',
        'current_occupancy',
        'status',
        'monthly_fee',
        'floor',
        'amenities',
        'description',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'current_occupancy' => 'integer',
        'floor' => 'integer',
        'monthly_fee' => 'decimal:2',
    ];

    public function allocations(): HasMany
    {
        return $this->hasMany(RoomAllocation::class);
    }

    public function activeAllocations(): HasMany
    {
        return $this->hasMany(RoomAllocation::class)->where('status', 'active');
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function availableBeds(): int
    {
        return max(0, $this->capacity - $this->current_occupancy);
    }

    public function isFull(): bool
    {
        return $this->current_occupancy >= $this->capacity;
    }

    /**
     * Recalculates current occupancy and updates room status accordingly.
     */
    public function syncOccupancy(): void
    {
        $activeCount = $this->activeAllocations()->count();
        $this->current_occupancy = $activeCount;

        if ($this->status !== 'maintenance') {
            $this->status = ($activeCount >= $this->capacity) ? 'full' : 'available';
        }

        $this->save();
    }
}
