<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\ProductsIndexController;
use App\Http\Controllers\Products\ProductsCrudController;
use App\Enums\RoleEnum;
use App\Enums\PermissionEnum;

Route::get('/', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/products', ProductsIndexController::class)
        ->name('products.index')
        ->can(PermissionEnum::PRODUCTS_READ->value);

    Route::middleware(sprintf('role:%s|%s', RoleEnum::ADMIN->value, RoleEnum::SUPERADMIN->value))->group(function () {
        Route::get('/admin/products', [ProductsCrudController::class, 'index'])->name('products.admin.index');
        Route::get('/admin/products/create', [ProductsCrudController::class, 'create'])
            ->name('products.admin.create')
            ->can(PermissionEnum::PRODUCTS_CREATE->value);
        Route::post('/admin/products', [ProductsCrudController::class, 'store'])
            ->name('products.admin.store')
            ->can(PermissionEnum::PRODUCTS_CREATE->value);
        Route::get('/admin/products/{product:sku}', [ProductsCrudController::class, 'edit'])
            ->name('products.admin.edit')
            ->can(PermissionEnum::PRODUCTS_UPDATE->value);
        Route::put('/admin/products/{product:sku}', [ProductsCrudController::class, 'update'])
            ->name('products.admin.update')
            ->can(PermissionEnum::PRODUCTS_UPDATE->value);
        Route::delete('/admin/products/{product:sku}', [ProductsCrudController::class, 'delete'])
            ->name('products.admin.delete')
            ->can(PermissionEnum::PRODUCTS_DELETE->value);
    });
});

require __DIR__.'/auth.php';
