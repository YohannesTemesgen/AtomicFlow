<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    protected $fillable = [
        'title',
        'description',
        'value',
        'priority',
        'status',
        'expected_close_date',
        'client_id',
        'stage_id',
        'user_id',
        'assigned_to_id',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'expected_close_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(AssignedPerson::class, 'assigned_to_id');
    }

    public function stageHistory(): HasMany
    {
        return $this->hasMany(LeadStageHistory::class)->orderBy('transitioned_at', 'desc');
    }

    public function updateStage($newStageId, $userId, $notes = null)
    {
        $fromStageId = $this->stage_id;
        
        // Update the lead's current stage
        $this->update(['stage_id' => $newStageId]);
        
        // Create stage history record
        $this->stageHistory()->create([
            'from_stage_id' => $fromStageId,
            'to_stage_id' => $newStageId,
            'user_id' => $userId,
            'notes' => $notes,
            'transitioned_at' => now(),
        ]);
        
        return $this;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeWon($query)
    {
        return $query->where('status', 'won');
    }

    public function scopeLost($query)
    {
        return $query->where('status', 'lost');
    }

    public function scopeByStage($query, $stageId)
    {
        return $query->where('stage_id', $stageId);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('client', function ($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
              });
        });
    }

    public function scopeDueSoon($query)
    {
        return $query->where('expected_close_date', '<=', now()->addDays(7))
                     ->where('status', 'active');
    }

    public function scopeOverdue($query)
    {
        return $query->where('expected_close_date', '<', now())
                     ->where('status', 'active');
    }
}
