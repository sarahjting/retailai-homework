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

        // admins do not get initialized with create/read/update
        // the reason is we need to be able to revoke these later
        $adminRole->givePermissionTo([
            $permissions[PermissionEnum::PRODUCTS_READ->value],
        ]);

        $merchantRole = Role::create(['name' => RoleEnum::MERCHANT]);
        $merchantRole->givePermissionTo([
            $permissions[PermissionEnum::PRODUCTS_READ->value],
        ]);
    }
}
