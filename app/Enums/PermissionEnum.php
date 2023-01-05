<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case USER_PERMISSIONS_UPDATE = "user_permissions_update";
    case PRODUCTS_CREATE = "products_create";
    case PRODUCTS_READ = "products_read";
    case PRODUCTS_UPDATE = "products_update";
    case PRODUCTS_DELETE = "products_delete";

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

    // array of permissions that can be edited via the superadmin panel
    public static function adminnablePermissions(): array
    {
        return [
            PermissionEnum::PRODUCTS_CREATE,
            PermissionEnum::PRODUCTS_READ,
            PermissionEnum::PRODUCTS_UPDATE,
            PermissionEnum::PRODUCTS_DELETE,
        ];
    }
}
