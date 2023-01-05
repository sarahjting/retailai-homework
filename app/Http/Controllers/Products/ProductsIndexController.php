<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Domains\Product\Actions\PaginateProductsAction;
use Illuminate\View\View;

class ProductsIndexController extends Controller
{
    public function __invoke(PaginateProductsAction $paginateProductsAction): View
    {
        return view('pages.products.index', [
            'products' => $paginateProductsAction->execute(),
        ]);
    }
}
