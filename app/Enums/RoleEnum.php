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
}
