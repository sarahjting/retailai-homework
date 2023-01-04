<?php

use App\Enums\RoleEnum;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\UserController;
use Illuminate\Support\Facades\Route;

Route::pattern('role', collect([RoleEnum::MERCHANT->value, RoleEnum::ADMIN->value])->join('|'));

// the requirements are ambiguous regarding whether any authorization is required for the admin endpoints
// we would normally have some kind of middleware here for authorization. IP checking?
Route::middleware('guest')->group(function () {
    Route::get('{role:name}/signup', [UserController::class, 'create'])->name('register');
    Route::post('{role:name}/signup', [UserController::class, 'store']);

    Route::get('{role:name}/signin', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('{role:name}/signin', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
