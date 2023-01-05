<?php

namespace Tests\Feature\Products\ProductsCrudController;

use App\Enums\PermissionEnum;
use App\Models\Product;
use App\Models\User;
use Closure;
use Database\Factories\ProductFactory;
use Database\Factories\UserFactory;
use Illuminate\Testing\TestResponse;
use Tests\DatabaseTestCase;
use Tests\Util\AuthorizationCase;
use Tests\Util\AuthorizationDataProvider;

class EditTest extends DatabaseTestCase
{
    private function editProduct(Product $product, ?User $user = null): TestResponse
    {
        if ($user) {
            $this->actingAs($user);
        }

        return $this->get(route('products.admin.edit', ['product' => $product]));
    }

    /**
     * @dataProvider provider_authorizes
     */
    public function test_authorizes(?UserFactory $userFactory, Closure $assertion): void
    {
        $assertion($this->editProduct(ProductFactory::new()->create(), $userFactory?->create()));
    }

    public function provider_authorizes(): array
    {
        return (new AuthorizationDataProvider)([
            fn (AuthorizationCase $case) => $case->guest()->redirectToMerchantLogin(),
            fn (AuthorizationCase $case) => $case->merchant()->forbidden(),
            fn (AuthorizationCase $case) => $case->admin()->forbidden(),
            fn (AuthorizationCase $case) => $case->admin()->permissions([PermissionEnum::PRODUCTS_UPDATE->value])->ok(),
            fn (AuthorizationCase $case) => $case->superadmin()->ok(),
        ]);
    }

    public function test_renders_edit_form(): void
    {
        $product = ProductFactory::new()->create();
        $this->editProduct($product, UserFactory::new()->superadmin()->create())
            ->assertOk()
            ->assertSeeText($product->name);
    }
}
