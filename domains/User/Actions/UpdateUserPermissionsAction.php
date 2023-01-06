<?php

namespace Domains\User\Actions;

use App\Enums\PermissionEnum;
use App\Models\User;

class UpdateUserPermissionsAction
{
    public function execute(User $user, array $roles, array $permissions): void
    {
        $user->syncRoles($roles);
        $user->syncPermissions(
            collect(PermissionEnum::filterAvailableToRoles($roles, $permissions))
                ->map(fn (PermissionEnum $enum) => $enum->value)
        );
    }
}
