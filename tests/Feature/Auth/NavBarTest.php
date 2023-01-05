<?php

namespace Tests\Feature\Auth;

use App\Enums\PermissionEnum;
use App\Models\User;
use Closure;
use Database\Factories\UserFactory;
use Illuminate\Testing\TestResponse;
use Tests\DatabaseTestCase;
use Tests\Util\AuthorizationCase;
use Tests\Util\AuthorizationDataProvider;

class NavBarTest extends DatabaseTestCase
{
    private function getIndex(?User $user = null): TestResponse
    {
        if ($user) {
            $this->actingAs($user);
        }

        return $this->get('/');
    }

    /**
     * @dataProvider provider_authorizes
     */
    public function test_authorizes(?UserFactory $userFactory, Closure $assertion): void
    {
        $assertion($this->getIndex($userFactory?->create()));
    }

    public function provider_authorizes(): array
    {
        return (new AuthorizationDataProvider)([
            fn (AuthorizationCase $case) => $case->guest()->redirectToMerchantLogin(),
            fn (AuthorizationCase $case) => $case->merchant()->assert(function (TestResponse $response) {
                $response->assertSee(route('products.index'));
                $response->assertDontSee(route('products.admin.index'));
            }),
            fn (AuthorizationCase $case) => $case->admin()->assert(function (TestResponse $response) {
                $response->assertSee(route('products.index'));
                $response->assertSee(route('products.admin.index'));
            }),
            fn (AuthorizationCase $case) => $case->superadmin()->assert(function (TestResponse $response) {
                $response->assertSee(route('products.index'));
                $response->assertSee(route('products.admin.index'));
            }),
        ]);
    }
}
