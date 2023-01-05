<?php

namespace Domains\User\Actions;

use App\Models\User;

class UpdateUserPermissionsAction
{
    public function execute(User $user, array $roles, array $permissions): void
    {
        $user->syncPermissions($permissions);
        $user->syncRoles($roles);
    }
}
