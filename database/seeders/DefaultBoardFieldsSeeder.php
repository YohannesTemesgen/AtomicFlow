<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BoardType;
use App\Services\TenantService;

class DefaultBoardFieldsSeeder extends Seeder
{
    public function run(): void
    {
        $boardTypes = BoardType::whereDoesntHave('fields')->get();

        foreach ($boardTypes as $boardType) {
            TenantService::createDefaultFields($boardType);
            TenantService::createDefaultStages($boardType);
        }
    }
}
