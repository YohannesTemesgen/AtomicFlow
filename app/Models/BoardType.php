<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoardType extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'theme_color',
        'icon',
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

    public function fields(): HasMany
    {
        return $this->hasMany(BoardField::class)->orderBy('position');
    }

    public function boards(): HasMany
    {
        return $this->hasMany(Board::class);
    }

    public function stages(): HasMany
    {
        return $this->hasMany(BoardStage::class)->orderBy('position');
    }
}
