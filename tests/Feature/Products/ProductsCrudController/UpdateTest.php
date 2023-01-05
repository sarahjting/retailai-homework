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

class UpdateTest extends DatabaseTestCase
{
    private function generateSku(): string
    {
        return 'ProductsCrudController_UpdateTest_sku';
    }

    private function updateProduct(Product $product, ?User $user = null, array $data = []): TestResponse
    {
        if ($user) {
            $this->actingAs($user);
        }

        return $this->put(route('products.admin.update', ['product' => $product]), array_merge([
            'name' => $product->name,
            'sku' => $product->sku,
            'description' => $product->description,
        ], $data));
    }

    /**
     * @dataProvider provider_authorizes
     */
    public function test_authorizes(?UserFactory $userFactory, Closure $assertion): void
    {
        $assertion($this->updateProduct(ProductFactory::new()->sku($this->generateSku())->create(), $userFactory?->create()));
    }

    public function provider_authorizes(): array
    {
        $assertOk = fn (TestResponse $response) => $response->assertRedirectToRoute('products.admin.edit', [
            'product' => $this->generateSku(),
        ]);
        return (new AuthorizationDataProvider)([
            fn (AuthorizationCase $case) => $case->guest()->redirectToMerchantLogin(),
            fn (AuthorizationCase $case) => $case->merchant()->forbidden(),
            fn (AuthorizationCase $case) => $case->admin()->forbidden(),
            fn (AuthorizationCase $case) => $case->admin()->permissions([PermissionEnum::PRODUCTS_UPDATE->value])->assert($assertOk),
            fn (AuthorizationCase $case) => $case->superadmin()->assert($assertOk),
        ]);
    }

    public function test_updates_product(): void
    {
        $product = ProductFactory::new()->create();

        $newSku = sprintf('new_%s', $this->generateSku());
        $this->updateProduct(
            product: $product,
            user: UserFactory::new()->superadmin()->create(),
            data: [
                'name' => 'new name',
                'sku' => $newSku,
                'description' => 'new description',
            ]
        )->assertRedirectToRoute('products.admin.edit', ['product' => $newSku]);

        $product->refresh();
        $this->assertEquals('new name', $product->name);
        $this->assertEquals($newSku, $product->sku);
        $this->assertEquals('new description', $product->description);
    }
}
