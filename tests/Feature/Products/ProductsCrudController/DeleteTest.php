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

class DeleteTest extends DatabaseTestCase
{
    private function deleteProduct(Product $product, ?User $user = null): TestResponse
    {
        if ($user) {
            $this->actingAs($user);
        }

        return $this->delete(route('products.admin.delete', ['product' => $product]));
    }

    /**
     * @dataProvider provider_authorizes
     */
    public function test_authorizes(?UserFactory $userFactory, Closure $assertion): void
    {
        $assertion($this->deleteProduct(ProductFactory::new()->create(), $userFactory?->create()));
    }

    public function provider_authorizes(): array
    {
        $assertOk = fn (TestResponse $response) => $response->assertRedirectToRoute('products.admin.index');
        return (new AuthorizationDataProvider)([
            fn (AuthorizationCase $case) => $case->guest()->redirectToMerchantLogin(),
            fn (AuthorizationCase $case) => $case->merchant()->forbidden(),
            fn (AuthorizationCase $case) => $case->admin()->forbidden(),
            fn (AuthorizationCase $case) => $case->admin()->permissions([PermissionEnum::PRODUCTS_DELETE->value])->assert($assertOk),
            fn (AuthorizationCase $case) => $case->superadmin()->assert($assertOk),
        ]);
    }

    public function test_deletes_product(): void
    {
        $product = ProductFactory::new()->create();

        $this->deleteProduct($product, user: UserFactory::new()->superadmin()->create())
            ->assertRedirectToRoute('products.admin.index');

        $product->refresh();
        $this->assertSoftDeleted($product);
    }
}
