<?php

namespace Tests\Feature\Users\UserPermissionsController;

use App\Models\User;
use Closure;
use Database\Factories\UserFactory;
use Illuminate\Testing\TestResponse;
use Tests\DatabaseTestCase;
use Tests\Util\AuthorizationCase;
use Tests\Util\AuthorizationDataProvider;

class EditTest extends DatabaseTestCase
{
    private function editUserPermissions(User $targetUser, ?User $user = null): TestResponse
    {
        if ($user) {
            $this->actingAs($user);
        }

        return $this->get(route('user_permissions.edit', ['user' => $targetUser]));
    }

    /**
     * @dataProvider provider_authorizes
     */
    public function test_authorizes(?UserFactory $userFactory, Closure $assertion): void
    {
        $assertion($this->editUserPermissions(UserFactory::new()->create(), $userFactory?->create()));
    }

    public function provider_authorizes(): array
    {
        return (new AuthorizationDataProvider)([
            fn (AuthorizationCase $case) => $case->guest()->redirectToMerchantLogin(),
            fn (AuthorizationCase $case) => $case->merchant()->forbidden(),
            fn (AuthorizationCase $case) => $case->admin()->forbidden(),
            fn (AuthorizationCase $case) => $case->superadmin()->ok(),
        ]);
    }

    public function test_renders_edit_form(): void
    {
        $user = UserFactory::new()->create();
        $this->editUserPermissions($user, UserFactory::new()->superadmin()->create())
            ->assertOk()
            ->assertSeeText($user->email);
    }
}
