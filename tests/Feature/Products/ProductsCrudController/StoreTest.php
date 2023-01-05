<?php

namespace Tests\Feature\Products\ProductsCrudController;

use App\Enums\PermissionEnum;
use App\Models\Product;
use App\Models\User;
use Closure;
use Database\Factories\ProductFactory;
use Database\Factories\UserFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\DatabaseTestCase;
use Tests\Util\AuthorizationCase;
use Tests\Util\AuthorizationDataProvider;

class StoreTest extends DatabaseTestCase
{
    private function generateSku(): string
    {
        // we want this to be unique so it doesn't clash during paratesting
        return 'ProductsCrudController_StoreTest_sku';
    }

    private function storeProduct(?User $user = null, array $data = []): TestResponse
    {
        if ($user) {
            $this->actingAs($user);
        }

        return $this->post(route('products.admin.store'), array_merge([
            'name' => Str::random(32),
            'sku' => $this->generateSku(),
            'image' => UploadedFile::fake()->image('foo.jpg'),
            'description' => Str::random(32),
        ], $data));
    }

    /**
     * @dataProvider provider_authorizes
     */
    public function test_authorizes(?UserFactory $userFactory, Closure $assertion): void
    {
        $assertion($this->storeProduct($userFactory?->create()));
    }

    public function provider_authorizes(): array
    {
        $assertOk = fn (TestResponse $response) => $response->assertRedirectToRoute('products.admin.edit', ['product' => $this->generateSku()]);
        return (new AuthorizationDataProvider)([
            fn (AuthorizationCase $case) => $case->guest()->redirectToMerchantLogin(),
            fn (AuthorizationCase $case) => $case->merchant()->forbidden(),
            fn (AuthorizationCase $case) => $case->admin()->forbidden(),
            fn (AuthorizationCase $case) => $case->admin()->permissions([PermissionEnum::PRODUCTS_CREATE->value])->assert($assertOk),
            fn (AuthorizationCase $case) => $case->superadmin()->assert($assertOk),
        ]);
    }

    public function test_creates_product(): void
    {
        $this->storeProduct(
            user: UserFactory::new()->superadmin()->create(),
            data: [
                'name' => 'foo',
                'sku' => 'ProductsCrudController_StoreTest_sku',
            ]
        )->assertRedirectToRoute('products.admin.edit', ['product' => $this->generateSku()]);

        $this->assertTrue(Product::where('name', 'foo')->where('sku', $this->generateSku())->exists());
    }
}
