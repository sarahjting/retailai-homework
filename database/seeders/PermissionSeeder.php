<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = collect(PermissionEnum::cases())
            ->mapWithKeys(fn (PermissionEnum $permission) => [$permission->value => Permission::create(['name' => $permission->value])]);

        $superAdminRole = Role::create(['name' => RoleEnum::SUPERADMIN]);

        /** @var Role $admin_role */
        $adminRole = Role::create(['name' => RoleEnum::ADMIN]);

        $merchantRole = Role::create(['name' => RoleEnum::MERCHANT]);
    }
}
