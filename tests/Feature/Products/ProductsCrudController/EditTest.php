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

    /**
     * @dataProvider provider_renders_delete_form
     */
    public function test_renders_delete_form(UserFactory $userFactory, Closure $assert): void
    {
        $product = ProductFactory::new()->create();
        $assert($this->editProduct($product, $userFactory->create()));
    }

    public function provider_renders_delete_form(): array
    {
        $assertSeeDelete = fn (TestResponse $res) => $res->assertOk()->assertSeeText('Delete product');
        $assertDontSeeDelete = fn (TestResponse $res) => $res->assertOk()->assertDontSeeText('Delete product');
        return (new AuthorizationDataProvider)([
            fn (AuthorizationCase $case) => $case->merchant()->forbidden(),
            fn (AuthorizationCase $case) => $case->admin()->permissions([PermissionEnum::PRODUCTS_UPDATE->value])->assert($assertDontSeeDelete),
            fn (AuthorizationCase $case) => $case->admin()->permissions([PermissionEnum::PRODUCTS_DELETE->value])->assert($assertSeeDelete),
            fn (AuthorizationCase $case) => $case->superadmin()->assert($assertSeeDelete),
        ]);
    }
}
