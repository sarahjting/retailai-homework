<?php

namespace Tests\Feature\Products\ProductsIndexController;

use App\Models\Product;
use App\Models\User;
use Closure;
use Database\Factories\ProductFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Testing\TestResponse;
use Tests\DatabaseTestCase;
use Tests\Util\AuthorizationCase;
use Tests\Util\AuthorizationDataProvider;

class IndexTest extends DatabaseTestCase
{
    private function indexProducts(?User $user = null, ?int $page = 1): TestResponse
    {
        if ($user) {
            $this->actingAs($user);
        }

        return $this->get(sprintf('products?page=%d', $page));
    }

    /**
     * @dataProvider provider_authorizes
     */
    public function test_authorizes(?UserFactory $userFactory, Closure $assertion): void
    {
        $assertion($this->indexProducts($userFactory?->create()));
    }

    public function provider_authorizes(): array
    {
        return (new AuthorizationDataProvider)([
            fn (AuthorizationCase $case) => $case->guest()->redirectToMerchantLogin(),
            fn (AuthorizationCase $case) => $case->merchant()->ok(),
            fn (AuthorizationCase $case) => $case->admin()->ok(),
            fn (AuthorizationCase $case) => $case->superadmin()->ok(),
        ]);
    }

    public function test_indexes_empty(): void
    {
        $this->indexProducts(UserFactory::new()->merchant()->create())
            ->assertOk()
            ->assertSeeText('No products');
    }

    public function test_indexes_expected_products(): void
    {
        /** @var Product[] $products */
        $products = ProductFactory::new()->createMany(3);
        $deletedProduct = ProductFactory::new()->deleted()->create();

        $res = $this->indexProducts(UserFactory::new()->merchant()->create())->assertOk();

        // see all existing products
        foreach ($products as $product) {
            $res->assertSeeText($product->name)->assertSeeText($product->sku)->assertSeeText($product->description);
        }

        // don't see deleted products
        $res->assertDontSeeText($deletedProduct->sku);
    }

    public function test_indexes_pagination(): void
    {
        $PER_PAGE = 8;

        /** @var Collection|Product[] $products */
        $products = ProductFactory::new()->createMany($PER_PAGE + 1)->sortBy(fn ($x) => strtolower($x->name))->values();

        $user = UserFactory::new()->merchant()->create();
        $res = $this->indexProducts($user)->assertOk();
        $res->assertSeeText($products[$PER_PAGE - 1]->sku);
        $res->assertDontSeeText($products[$PER_PAGE]->sku);

        $res = $this->indexProducts($user, 2)->assertOk();
        $res->assertDontSeeText($products[$PER_PAGE - 1]->sku);
        $res->assertSeeText($products[$PER_PAGE]->sku);
    }
}
