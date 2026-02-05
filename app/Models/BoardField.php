<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoardField extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_type_id',
        'name',
        'label',
        'type',
        'placeholder',
        'default_value',
        'options',
        'validation_rules',
        'is_required',
        'is_active',
        'position',
        'settings',
    ];

    protected $casts = [
        'options' => 'array',
        'validation_rules' => 'array',
        'settings' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public const FIELD_TYPES = [
        'text' => 'Text Input',
        'textarea' => 'Text Area',
        'summernote' => 'Rich Text Editor (Summernote)',
        'number' => 'Number',
        'email' => 'Email',
        'phone' => 'Phone',
        'url' => 'URL',
        'date' => 'Date',
        'datetime' => 'Date & Time',
        'select' => 'Dropdown Select',
        'multiselect' => 'Multi Select',
        'checkbox' => 'Checkbox',
        'radio' => 'Radio Buttons',
        'file' => 'File Upload',
        'color' => 'Color Picker',
        'currency' => 'Currency',
    ];

    public function boardType(): BelongsTo
    {
        return $this->belongsTo(BoardType::class);
    }
}
