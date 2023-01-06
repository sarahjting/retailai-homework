<?php

namespace Domains\User\Actions;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateUserAction
{
    public function execute(string $name, string $email, string $password, Role $role): User
    {
        /** @var User $user */
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->assignRole($role);
        $user->syncPermissions(
            collect(RoleEnum::fromModel($role)
                ->defaultPermissions())
                ->map(fn (PermissionEnum $enum) => $enum->value)
        );

        return $user;
    }
}
