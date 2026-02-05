<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoardCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_id',
        'stage_id',
        'title',
        'field_values',
        'position',
        'created_by',
        'assigned_to',
    ];

    protected $casts = [
        'field_values' => 'array',
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(BoardStage::class, 'stage_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getFieldValue(string $fieldName)
    {
        return $this->field_values[$fieldName] ?? null;
    }
}
