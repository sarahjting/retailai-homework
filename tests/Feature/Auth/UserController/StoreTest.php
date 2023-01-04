<?php

namespace Tests\Feature\Auth\UserController;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Testing\TestResponse;
use Tests\DatabaseTestCase;

class StoreTest extends DatabaseTestCase
{
    public function test_new_merchants_can_register(): void
    {
        $email = 'test_new_merchants_can_register@example.com';

        $this->register(RoleEnum::MERCHANT, ['email' => $email])->assertRedirect(RouteServiceProvider::HOME);

        /** @var User|null $user */
        $user = User::where('email', $email)->first();

        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole(RoleEnum::MERCHANT->value));
        $this->assertFalse($user->hasRole(RoleEnum::ADMIN->value));
        $this->assertFalse($user->hasRole(RoleEnum::SUPERADMIN->value));
    }

    public function test_new_admins_can_register(): void
    {
        $email = 'test_new_admins_can_register@example.com';

        $this->register(RoleEnum::ADMIN, ['email' => $email])
            ->assertRedirect(RouteServiceProvider::HOME);

        /** @var User|null $user */
        $user = User::where('email', $email)->first();

        $this->assertNotNull($user);
        $this->assertFalse($user->hasRole(RoleEnum::MERCHANT->value));
        $this->assertTrue($user->hasRole(RoleEnum::ADMIN->value));
        $this->assertFalse($user->hasRole(RoleEnum::SUPERADMIN->value));
    }

    public function test_new_superadmins_cannot_register(): void
    {
        $this->register(RoleEnum::SUPERADMIN)->assertNotFound();
    }

    private function register(RoleEnum $role, array $data = []): TestResponse
    {
        return $this->post(sprintf("%s/signup", $role->value), array_merge([
            'name' => 'Test User',
            'email' => 'test_new_merchants_can_register@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ], $data));
    }
}
