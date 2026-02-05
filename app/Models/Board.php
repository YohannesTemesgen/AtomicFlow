<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Board extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'board_type_id',
        'name',
        'description',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function boardType(): BelongsTo
    {
        return $this->belongsTo(BoardType::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(BoardCard::class);
    }

    public function stages(): HasMany
    {
        return $this->hasMany(BoardStage::class, 'board_type_id', 'board_type_id');
    }
}
