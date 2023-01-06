<?php

namespace Tests\Feature\Users\UserPermissionsController;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\User;
use Closure;
use Database\Factories\UserFactory;
use Illuminate\Testing\TestResponse;
use Tests\DatabaseTestCase;
use Tests\Util\AuthorizationCase;
use Tests\Util\AuthorizationDataProvider;

class UpdateTest extends DatabaseTestCase
{
    private function generateUlid(): string
    {
        // doesn't really matter what this is, it just has to be unique and predictable
        return '01GP0J4CPRHYX6T3AR084CFHM5';
    }

    private function updatePermissions(User $targetUser, ?User $user = null, array $data = []): TestResponse
    {
        if ($user) {
            $this->actingAs($user);
        }

        $endpoint = route('user_permissions.update', ['user' => $targetUser]);
        return $this->from($endpoint)
            ->put($endpoint, array_merge([
                'roles' => $targetUser->roles()->pluck('name')->toArray(),
                'permissions' => $targetUser->getDirectPermissions()->pluck('name')->toArray(),
            ], $data));
    }

    /**
     * @dataProvider provider_authorizes
     */
    public function test_authorizes(?UserFactory $userFactory, Closure $assertion): void
    {
        $user = UserFactory::new()->ulid($this->generateUlid())->create();
        $assertion($this->updatePermissions($user, $userFactory?->create()));
    }

    public function provider_authorizes(): array
    {
        $assertOk = fn (TestResponse $response) => $response->assertRedirectToRoute('user_permissions.edit', ['user' => $this->generateUlid()]);
        return (new AuthorizationDataProvider)([
            fn (AuthorizationCase $case) => $case->guest()->redirectToMerchantLogin(),
            fn (AuthorizationCase $case) => $case->merchant()->forbidden(),
            fn (AuthorizationCase $case) => $case->admin()->forbidden(),
            fn (AuthorizationCase $case) => $case->admin()->forbidden(),
            fn (AuthorizationCase $case) => $case->superadmin()->assert($assertOk),
        ]);
    }

    public function test_grant_roles(): void
    {
        $user = UserFactory::new()->ulid($this->generateUlid())->create();

        $this->updatePermissions(
            targetUser: $user,
            user: UserFactory::new()->superadmin()->create(),
            data: [
                'roles' => [RoleEnum::ADMIN->value, RoleEnum::MERCHANT->value],
            ]
        )->assertRedirectToRoute('user_permissions.edit', ['user' => $user]);

        $user->refresh();
        $this->assertTrue($user->hasAllRoles([RoleEnum::ADMIN->value, RoleEnum::MERCHANT->value]));
    }

    public function test_fails_to_grant_superadmin(): void
    {
        $user = UserFactory::new()->ulid($this->generateUlid())->create();

        $this->updatePermissions(
            targetUser: $user,
            user: UserFactory::new()->superadmin()->create(),
            data: [
                'roles' => [RoleEnum::SUPERADMIN->value],
            ]
        )->assertSessionHasErrorsIn('roles');

        $user->refresh();
        $this->assertFalse($user->hasRole(RoleEnum::SUPERADMIN->value));
    }

    public function test_grant_admin_permissions_to_admin(): void
    {
        $user = UserFactory::new()->ulid($this->generateUlid())->admin()->create();

        $this->updatePermissions(
            targetUser: $user,
            user: UserFactory::new()->superadmin()->create(),
            data: [
                'permissions' => [PermissionEnum::PRODUCTS_UPDATE->value, PermissionEnum::PRODUCTS_CREATE->value],
            ]
        )->assertRedirectToRoute('user_permissions.edit', ['user' => $user->ulid]);

        $user->refresh();
        $this->assertTrue($user->hasAllPermissions([PermissionEnum::PRODUCTS_UPDATE->value, PermissionEnum::PRODUCTS_CREATE->value]));
    }

    public function test_grant_admin_permissions_to_merchant_user(): void
    {
        $user = UserFactory::new()->ulid($this->generateUlid())->merchant()->create();

        $this->updatePermissions(
            targetUser: $user,
            user: UserFactory::new()->superadmin()->create(),
            data: [
                'permissions' => [PermissionEnum::PRODUCTS_UPDATE->value, PermissionEnum::PRODUCTS_CREATE->value],
            ]
        )
            ->assertRedirectToRoute('user_permissions.edit', ['user' => $user->ulid])
            ->assertSessionHasErrorsIn('permissions');

        $user->refresh();
        $this->assertFalse($user->hasAnyPermission([PermissionEnum::PRODUCTS_UPDATE->value, PermissionEnum::PRODUCTS_CREATE->value]));
    }

    public function test_fails_to_grant_superuser_permissions(): void
    {
        $user = UserFactory::new()->ulid($this->generateUlid())->create();

        $this->updatePermissions(
            targetUser: $user,
            user: UserFactory::new()->superadmin()->create(),
            data: [
                'permissions' => [PermissionEnum::USER_PERMISSIONS_UPDATE->value],
            ]
        )->assertSessionHasErrorsIn('permissions');

        $user->refresh();
        $this->assertFalse($user->hasAnyPermission([PermissionEnum::USER_PERMISSIONS_UPDATE->value]));
    }
}
