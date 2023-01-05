<?php

namespace Tests\Feature\Products\ProductsCrudController;

use App\Enums\PermissionEnum;
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
    private function indexProducts(?User $user = null, int $page = 1): TestResponse
    {
        if ($user) {
            $this->actingAs($user);
        }

        return $this->get(route('products.admin.index', ['page' => $page]));
    }

    /************************************************
     * INDEX AUTHORIZATION
     ************************************************/

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
            fn (AuthorizationCase $case) => $case->merchant()->forbidden(),
            fn (AuthorizationCase $case) => $case->admin()->ok(),
            fn (AuthorizationCase $case) => $case->superadmin()->ok(),
        ]);
    }

    /************************************************
     * CREATE AUTHORIZATION
     ************************************************/
    public function test_show_link_to_create_page_to_authorized_admins(): void
    {
        $this->indexProducts(UserFactory::new()->admin()->permissions([PermissionEnum::PRODUCTS_CREATE->value])->create())
            ->assertOk()
            ->assertSee(route('products.admin.create'));
    }

    public function test_do_not_show_link_to_create_page_to_unauthorized_admins(): void
    {
        $this->indexProducts(UserFactory::new()->admin()->create())
            ->assertOk()
            ->assertDontSee(route('products.admin.create'));
    }

    /************************************************
     * UPDATE AUTHORIZATION
     ************************************************/
    public function test_show_link_to_edit_page_to_authorized_admins(): void
    {
        $product = ProductFactory::new()->create();
        $this->indexProducts(UserFactory::new()->admin()->permissions([PermissionEnum::PRODUCTS_UPDATE->value])->create())
            ->assertOk()
            ->assertSeeText($product->sku)
            ->assertSee(route('products.admin.edit', ['product' => $product]));
    }

    public function test_do_not_show_link_to_edit_page_to_unauthorized_admins(): void
    {
        $product = ProductFactory::new()->create();
        $this->indexProducts(UserFactory::new()->admin()->create())
            ->assertOk()
            ->assertSeeText($product->sku)
            ->assertDontSee(route('products.admin.edit', ['product' => $product]));
    }

    /************************************************
     * INDEX CONTENT
     ************************************************/
    public function test_indexes_empty(): void
    {
        $this->indexProducts(UserFactory::new()->admin()->create())
            ->assertOk()
            ->assertSeeText('No products');
    }

    public function test_indexes_expected_products(): void
    {
        /** @var Product[] $products */
        $products = ProductFactory::new()->createMany(3);
        $deletedProduct = ProductFactory::new()->deleted()->create();

        $res = $this->indexProducts(UserFactory::new()->admin()->create())->assertOk();

        // see all existing products
        foreach ($products as $product) {
            $res->assertSeeText($product->name)->assertSeeText($product->sku);
        }

        // don't see deleted products
        $res->assertDontSeeText($deletedProduct->sku);
    }

    public function test_indexes_pagination(): void
    {
        $PER_PAGE = 8;

        /** @var Collection|Product[] $products */
        $products = ProductFactory::new()->createMany($PER_PAGE + 1)->sortBy(fn ($x) => strtolower($x->name))->values();

        $user = UserFactory::new()->admin()->create();
        $res = $this->indexProducts($user)->assertOk();
        $res->assertSeeText($products[$PER_PAGE - 1]->sku);
        $res->assertDontSeeText($products[$PER_PAGE]->sku);

        $res = $this->indexProducts($user, 2)->assertOk();
        $res->assertDontSeeText($products[$PER_PAGE - 1]->sku);
        $res->assertSeeText($products[$PER_PAGE]->sku);
    }
}
