<?php

namespace Database\Factories;

use App\Enums\StorageDiskEnum;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductFactory extends EloquentModelFactory
{
    private ?string $sku = null;
    private bool $isDeleted = false;

    public static function new(): ProductFactory
    {
        return new ProductFactory();
    }

    public function sku(string $sku): ProductFactory
    {
        $factory = clone $this;
        $factory->sku = $sku;
        return $factory;
    }

    public function deleted(): ProductFactory
    {
        $factory = clone $this;
        $factory->isDeleted = true;
        return $factory;
    }

    public function create(): Product
    {
        $product = new Product();
        $product->name = 'Product Name ' . Str::random(5);
        $product->sku = $this->sku ?? Str::random(32);
        $product->image_disk = StorageDiskEnum::LOCAL;
        $product->image_extension = 'jpg';
        $product->description = sprintf('This is a product description for %s', $product->name);
        $product->deleted_at = $this->isDeleted ? now() : null;
        $product->save();

        return $product;
    }
}
