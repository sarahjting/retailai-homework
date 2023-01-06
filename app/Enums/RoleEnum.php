<?php

namespace App\Enums;

use Spatie\Permission\Models\Role;

enum RoleEnum: string
{
    case SUPERADMIN = "superadmin";
    case ADMIN = "admin";
    case MERCHANT = "merchant";

    public function label(): string
    {
        return match ($this)
        {
            RoleEnum::SUPERADMIN => 'Super admin',
            RoleEnum::ADMIN => 'Admin',
            RoleEnum::MERCHANT => 'Merchant',
        };
    }

    public static function fromModel(Role $role): ?RoleEnum
    {
        return RoleEnum::tryFrom($role->name);
    }

    // permissions which should be attached to a user by default, but are revokable
    public function defaultPermissions(): array
    {
        return $this->availablePermissions();
    }

    // permissions which are allowed to be attached to the role
    public function availablePermissions(): array
    {
        return match ($this)
        {
            RoleEnum::ADMIN => [
                PermissionEnum::PRODUCTS_READ,
                PermissionEnum::PRODUCTS_CREATE,
                PermissionEnum::PRODUCTS_UPDATE,
                PermissionEnum::PRODUCTS_DELETE,
            ],
            RoleEnum::MERCHANT => [
                PermissionEnum::PRODUCTS_READ,
            ],
            default => [],
        };
    }

    // array of roles that can be edited via the superadmin panel
    public static function adminnableRoles(): array
    {
        return [
            RoleEnum::ADMIN,
            RoleEnum::MERCHANT,
        ];
    }

    public static function fromArray(iterable $roles): array
    {
        return collect($roles)
            ->map(fn ($enum) => is_string($enum) ? RoleEnum::tryFrom($enum) : $enum)
            ->map(fn ($enum) => $enum instanceof Role ? RoleEnum::fromModel($enum) : $enum)
            ->filter()
            ->toArray();
    }
}
