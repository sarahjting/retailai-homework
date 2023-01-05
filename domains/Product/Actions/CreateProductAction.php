<?php

namespace Domains\Product\Actions;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CreateProductAction
{
    public function execute(string $name, string $sku, string $description, UploadedFile $image): Product
    {
        $product = new Product();
        $product->name = $name;
        $product->sku = $sku;
        $product->description = $description;
        $product->image_disk = config('filesystems.default');
        $product->image_extension = $image->extension();

        Storage::disk($product->image_disk->value)->put($product->image_path, $image->getContent());

        $product->save();

        return $product;
    }
}
