<?php

namespace Tests\Feature\Products\ProductsCrudController;

use App\Enums\PermissionEnum;
use App\Models\User;
use Closure;
use Database\Factories\UserFactory;
use Illuminate\Testing\TestResponse;
use Tests\DatabaseTestCase;
use Tests\Util\AuthorizationCase;
use Tests\Util\AuthorizationDataProvider;

class CreateTest extends DatabaseTestCase
{
    private function create(?User $user = null): TestResponse
    {
        if ($user) {
            $this->actingAs($user);
        }

        return $this->get('admin/products/create');
    }

    /**
     * @dataProvider provider_authorizes
     */
    public function test_authorizes(?UserFactory $userFactory, Closure $assertion): void
    {
        $assertion($this->create($userFactory?->create()));
    }

    public function provider_authorizes(): array
    {
        return (new AuthorizationDataProvider)([
            fn (AuthorizationCase $case) => $case->guest()->redirectToMerchantLogin(),
            fn (AuthorizationCase $case) => $case->merchant()->forbidden(),
            fn (AuthorizationCase $case) => $case->admin()->forbidden(),
            fn (AuthorizationCase $case) => $case->admin()->permissions([PermissionEnum::PRODUCTS_CREATE->value])->ok(),
            fn (AuthorizationCase $case) => $case->superadmin()->ok(),
        ]);
    }

    public function test_renders_create_form(): void
    {
        $this->create(UserFactory::new()->superadmin()->create())
            ->assertOk()
            ->assertSeeText('Add new product');
    }
}
