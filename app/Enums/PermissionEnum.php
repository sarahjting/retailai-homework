<?php

namespace App\Enums;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

enum PermissionEnum: string
{
    case USER_PERMISSIONS_UPDATE = "user_permissions_update";
    case PRODUCTS_CREATE = "products_create";
    case PRODUCTS_READ = "products_read";
    case PRODUCTS_UPDATE = "products_update";
    case PRODUCTS_DELETE = "products_delete";

    public static function fromModel(Permission $permission): ?PermissionEnum
    {
        return PermissionEnum::tryFrom($permission->name);
    }

    public function label(): string
    {
        return match ($this)
        {
            PermissionEnum::USER_PERMISSIONS_UPDATE => 'Update user permissions',
            PermissionEnum::PRODUCTS_CREATE => 'Create products',
            PermissionEnum::PRODUCTS_READ => 'Read products',
            PermissionEnum::PRODUCTS_UPDATE => 'Update products',
            PermissionEnum::PRODUCTS_DELETE => 'Delete products',
        };
    }

    public static function fromArray(iterable $permissions): array
    {
        return collect($permissions)
            ->map(fn ($enum) => is_string($enum) ? PermissionEnum::tryFrom($enum) : $enum)
            ->map(fn ($enum) => $enum instanceof Permission ? PermissionEnum::fromModel($enum) : $enum)
            ->filter()
            ->toArray();
    }

    public function isAvailableToRoles(iterable $roles): bool
    {
        return collect(RoleEnum::fromArray($roles))
            ->map(fn (RoleEnum $enum) => $enum->availablePermissions())
            ->flatten()
            ->contains($this);
    }

    public static function filterAvailableToRoles(iterable $roles, iterable $permissions): array
    {
        $availablePermissions = collect(RoleEnum::fromArray($roles))->map(fn(RoleEnum $enum) => $enum->availablePermissions())->flatten();
        return collect(PermissionEnum::fromArray($permissions))->filter(fn (PermissionEnum $enum) => $availablePermissions->contains($enum))->toArray();
    }
}
