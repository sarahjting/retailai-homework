<?php

namespace Domains\Product\Actions;

use App\Models\Product;

class DeleteProductAction
{
    public function execute(Product $product): void
    {
        $product->delete();

        // since this is soft deleting, I wouldn't normally delete the image, but could add it if desired
        // Storage::disk($product->image_disk)->delete($product->image_path);
    }
}
