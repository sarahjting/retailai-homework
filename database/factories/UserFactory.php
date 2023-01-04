<?php

namespace Database\Factories;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Support\Str;

class UserFactory
{
    private array $roles = [];
    private array $permissions = [];

    public static function new(): UserFactory
    {
        return new UserFactory();
    }

    public function create(): User
    {
        $user = new User();
        $user->name = fake()->name;
        $user->email = fake()->unique()->safeEmail();
        $user->email_verified_at = now();
        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password
        $user->remember_token = Str::random(10);
        $user->save();

        $user->syncRoles($this->roles);
        $user->syncPermissions($this->permissions);

        return $user;
    }

    public function merchant(): UserFactory
    {
        $factory = clone $this;
        return $factory->roles([RoleEnum::MERCHANT->value]);
    }

    public function admin(): UserFactory
    {
        $factory = clone $this;
        return $factory->roles([RoleEnum::ADMIN->value]);
    }

    public function superadmin(): UserFactory
    {
        $factory = clone $this;
        return $factory->roles([RoleEnum::SUPERADMIN->value]);
    }

    public function roles(array $roles): UserFactory
    {
        $factory = clone $this;
        $factory->roles = $roles;
        return $factory;
    }

    public function permissions(array $permissions): UserFactory
    {
        $factory = clone $this;
        $factory->permissions = $permissions;
        return $factory;
    }
}
