<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case USER_ROLES_UPDATE = "user_roles_update";
    case USER_PERMISSIONS_UPDATE = "user_permissions_update";
    case PRODUCTS_CREATE = "products_create";
    case PRODUCTS_READ = "products_read";
    case PRODUCTS_UPDATE = "products_update";
    case PRODUCTS_DELETE = "products_delete";

    public function label(): string
    {
        return match ($this)
        {
            PermissionEnum::USER_ROLES_UPDATE => 'Update user roles',
            PermissionEnum::USER_PERMISSIONS_UPDATE => 'Update user permissions',
            PermissionEnum::PRODUCTS_CREATE => 'Create products',
            PermissionEnum::PRODUCTS_READ => 'Read products',
            PermissionEnum::PRODUCTS_UPDATE => 'Update products',
            PermissionEnum::PRODUCTS_DELETE => 'Delete products',
        };
    }
}
