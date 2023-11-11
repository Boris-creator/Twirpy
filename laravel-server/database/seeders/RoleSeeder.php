<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Models\Role;
use App\Traits\RefreshSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class RoleSeeder extends Seeder
{
    use RefreshSeeder;

    protected function getModel(): string
    {
        return Role::class;
    }

    public function run(): void
    {
        $roles = Collection::make([
            Role::name('moderator')->can([Permission::DELETE_BOOKS->value, Permission::DOWNLOAD_FREE->value]),
        ]);
        $this->refresh($roles, 'name', 'permissions', 'name');
    }
}
