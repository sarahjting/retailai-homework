<?php

namespace Domains\Product\Actions;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateProductAction
{
    public function execute(Product $product, ?string $name = null, ?string $sku = null, ?string $description = null, ?UploadedFile $image = null): void
    {
        $oldImagePath = $product->image_path;

        if ($name !== null) {
            $product->name = $name;
        }

        if ($sku !== null) {
            $product->sku = $sku;
        }

        if ($description !== null) {
            $product->description = $description;
        }

        // I'm going to assume an uploaded image will never change disk, but normally we would have to account
        // for moving the image from disk to disk if needed
        if ($product->image_path !== $oldImagePath) {
            Storage::disk($product->image_disk->value)->move($oldImagePath, $product->image_path);
        }

        if ($image) {
            $product->image_extension = $image->extension();
            Storage::disk($product->image_disk->value)->put($product->image_path, $image->getContent());
        }

        $product->save();
    }
}
