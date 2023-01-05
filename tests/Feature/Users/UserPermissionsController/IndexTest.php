<?php

namespace Tests\Feature\Users\UserPermissionsController;

use App\Models\User;
use Closure;
use Database\Factories\UserFactory;
use Illuminate\Testing\TestResponse;
use Tests\DatabaseTestCase;
use Tests\Util\AuthorizationCase;
use Tests\Util\AuthorizationDataProvider;

class IndexTest extends DatabaseTestCase
{
    private function indexUsers(?User $user = null, int $page = 1): TestResponse
    {
        if ($user) {
            $this->actingAs($user);
        }

        return $this->get(route('user_permissions.index', ['page' => $page]));
    }

    /************************************************
     * INDEX AUTHORIZATION
     ************************************************/

    /**
     * @dataProvider provider_authorizes
     */
    public function test_authorizes(?UserFactory $userFactory, Closure $assertion): void
    {
        $assertion($this->indexUsers($userFactory?->create()));
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

    public function test_indexes_expected_users(): void
    {
        /** @var User[] $products */
        $users = UserFactory::new()->createMany(3);

        $res = $this->indexUsers(UserFactory::new()->superadmin()->create())->assertOk();

        foreach ($users as $user) {
            $res->assertSeeText($user->name)->assertSeeText($user->email);
        }
    }
}
