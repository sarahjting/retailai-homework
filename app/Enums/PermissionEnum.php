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
}
