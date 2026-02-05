<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\BoardType;
use App\Models\BoardField;
use App\Models\BoardStage;
use Illuminate\Support\Str;

class TenantService
{
    public static function createDefaultBoardType(Tenant $tenant): BoardType
    {
        $boardType = BoardType::create([
            'tenant_id' => $tenant->id,
            'name' => 'Task Board',
            'slug' => 'task-board',
            'description' => 'Default task management board',
            'theme_color' => '#10B981',
            'icon' => 'view_kanban',
        ]);

        self::createDefaultFields($boardType);
        self::createDefaultStages($boardType);

        return $boardType;
    }

    public static function createDefaultFields(BoardType $boardType): void
    {
        $defaultFields = [
            [
                'name' => 'title',
                'label' => 'Title',
                'type' => 'text',
                'placeholder' => 'Enter task title',
                'is_required' => true,
                'position' => 1,
            ],
            [
                'name' => 'description',
                'label' => 'Description',
                'type' => 'summernote',
                'placeholder' => 'Enter task description',
                'is_required' => false,
                'position' => 2,
            ],
            [
                'name' => 'priority',
                'label' => 'Priority',
                'type' => 'select',
                'placeholder' => 'Select priority',
                'is_required' => false,
                'position' => 3,
                'options' => [
                    ['value' => 'low', 'label' => 'Low'],
                    ['value' => 'medium', 'label' => 'Medium'],
                    ['value' => 'high', 'label' => 'High'],
                    ['value' => 'urgent', 'label' => 'Urgent'],
                ],
            ],
            [
                'name' => 'due_date',
                'label' => 'Due Date',
                'type' => 'date',
                'placeholder' => 'Select due date',
                'is_required' => false,
                'position' => 4,
            ],
            [
                'name' => 'estimated_hours',
                'label' => 'Estimated Hours',
                'type' => 'number',
                'placeholder' => 'Enter estimated hours',
                'is_required' => false,
                'position' => 5,
            ],
            [
                'name' => 'tags',
                'label' => 'Tags',
                'type' => 'multiselect',
                'placeholder' => 'Select tags',
                'is_required' => false,
                'position' => 6,
                'options' => [
                    ['value' => 'bug', 'label' => 'Bug'],
                    ['value' => 'feature', 'label' => 'Feature'],
                    ['value' => 'improvement', 'label' => 'Improvement'],
                    ['value' => 'documentation', 'label' => 'Documentation'],
                ],
            ],
        ];

        foreach ($defaultFields as $field) {
            BoardField::create(array_merge($field, [
                'board_type_id' => $boardType->id,
            ]));
        }
    }

    public static function createDefaultStages(BoardType $boardType): void
    {
        $defaultStages = [
            ['name' => 'Backlog', 'slug' => 'backlog', 'color' => '#6B7280', 'position' => 1],
            ['name' => 'To Do', 'slug' => 'todo', 'color' => '#3B82F6', 'position' => 2],
            ['name' => 'In Progress', 'slug' => 'in-progress', 'color' => '#F59E0B', 'position' => 3],
            ['name' => 'Review', 'slug' => 'review', 'color' => '#8B5CF6', 'position' => 4],
            ['name' => 'Done', 'slug' => 'done', 'color' => '#10B981', 'position' => 5],
        ];

        foreach ($defaultStages as $stage) {
            BoardStage::create(array_merge($stage, [
                'board_type_id' => $boardType->id,
            ]));
        }
    }

    public static function getCurrentTenant(): ?Tenant
    {
        $user = auth()->user();
        return $user ? $user->tenant : null;
    }
}
