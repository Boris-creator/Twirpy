<?php

namespace App\Traits;

use App\Enums\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait HasPermissions
{
    public function hasPermission(Permission $permission): bool
    {
        return $this->roles()->whereHas('permissions', function (Builder $rolePermission) use ($permission) {
            $rolePermission->where('name', '=', $permission->value);
        })->exists();
    }

    public function getPermissions(): Collection
    {
        $rolePermissions = $this->roles()
            ->with('permissions')->get()->pluck('permissions')->flatten(1);

        return $rolePermissions->merge(Collection::make($this->permissions()))->unique('id');
    }

    public function permissions(): MorphToMany
    {
        return $this->morphToMany(related: \App\Models\Permission::class, name: 'has_permissions');
    }
}
