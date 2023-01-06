<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Domains\Product\Actions\CreateProductAction;
use Domains\Product\Actions\DeleteProductAction;
use Domains\Product\Actions\PaginateProductsAction;
use Domains\Product\Actions\UpdateProductAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductsCrudController extends Controller
{
    public function index(PaginateProductsAction $paginateProductsAction): View
    {
        return view('pages.products.admin.index', [
            'products' => $paginateProductsAction->execute(),
        ]);
    }

    public function create(): View
    {
        return view('pages.products.admin.create');
    }

    public function store(CreateProductRequest $request, CreateProductAction $createProductAction): RedirectResponse
    {
        $product = $createProductAction->execute(
            name: $request->name,
            sku: $request->sku,
            description: $request->description ?? '',
            image: $request->image,
        );

        return redirect()->to(route('products.admin.edit', ['product' => $product]));
    }

    public function edit(Product $product): View
    {
        return view('pages.products.admin.edit', [
            'product' => $product,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product, UpdateProductAction $updateProductAction): RedirectResponse
    {
        $updateProductAction->execute(
            product: $product,
            name: $request->name,
            sku: $request->sku,
            description: $request->description ?? '',
            image: $request->image,
        );
        return redirect()->to(route('products.admin.edit', ['product' => $product]));
    }

    public function delete(Product $product, DeleteProductAction $deleteProductAction): RedirectResponse
    {
        $deleteProductAction->execute($product);
        return redirect()->to(route('products.admin.index'));
    }
}
