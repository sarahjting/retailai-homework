<?php

namespace App\Enums;

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

    // these are the permissions which should be attached to a user by default, but are revokable
    public function defaultPermissions(): array
    {
        return match ($this)
        {
            RoleEnum::ADMIN => [
                PermissionEnum::PRODUCTS_CREATE,
                PermissionEnum::PRODUCTS_UPDATE,
                PermissionEnum::PRODUCTS_DELETE,
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
}
