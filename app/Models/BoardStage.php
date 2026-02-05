<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoardStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_type_id',
        'name',
        'slug',
        'color',
        'position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function boardType(): BelongsTo
    {
        return $this->belongsTo(BoardType::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(BoardCard::class, 'stage_id');
    }
}
