<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 *
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role query()
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereName($value)
 *
 * @mixin Eloquent
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function permissions(): MorphToMany
    {
        return $this->morphToMany(related: Permission::class, name: 'has_permissions');
    }

    public static function name(string $roleName): Role
    {
        return new Role(['name' => $roleName]);
    }

    public function basedOn(...$roleNames): Role
    {
        $roles = self::query()->whereIn('name', $roleNames)
            ->with(['permissions'])
            ->get();
        $roles->each(function (Role $role) {
            $this->permissions = $this->permissions->merge($role->permissions);
        });

        return $this;
    }

    public function can(mixed $permissions): Model
    {
        $permissions = (array) $permissions;
        $existingPermissions = Permission::whereIn('name', $permissions)->get();
        $this->permissions = $this->permissions->merge($existingPermissions);

        return $this;
    }

    public function except(mixed $permissions): Model
    {
        $permissions = (array) $permissions;
        $this->permissions = $this->permissions->whereNotIn('name', $permissions);

        return $this;
    }
}
