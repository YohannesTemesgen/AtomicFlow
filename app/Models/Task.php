<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'type',
        'due_date',
        'stage_id',
        'user_id',
        'assigned_to_id',
        'client_id',
        'project_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function stage(): BelongsTo
    {
        return $this->belongsTo(TaskStage::class, 'stage_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(AssignedPerson::class, 'assigned_to_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function updateStage($newStageId, $userId, $notes = null)
    {
        $this->update(['stage_id' => $newStageId]);
        return $this;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByStage($query, $stageId)
    {
        return $query->where('stage_id', $stageId);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())->where('status', 'active');
    }

    public function scopeDueSoon($query)
    {
        return $query->where('due_date', '<=', now()->addDays(7))
                     ->where('due_date', '>=', now())
                     ->where('status', 'active');
    }
}
