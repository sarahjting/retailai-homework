<?php

namespace Domains\Product\Actions;

use App\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;

class PaginateProductsAction
{
    public function execute(): Paginator
    {
        return Product::orderBy('name')->orderBy('id')->paginate(8);
    }
}
