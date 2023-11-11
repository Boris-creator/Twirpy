<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Traits\RefreshSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class PermissionSeeder extends Seeder
{
    use RefreshSeeder;

    protected function getModel(): string
    {
        return Permission::class;
    }

    public function run(): void
    {
        $this->refresh(
            Collection::make((\App\Enums\Permission::cases()))
                ->map(function (\App\Enums\Permission $permission): Permission {
                    return new Permission(['name' => $permission->value]);
                }),
            'name'
        );
    }
}
