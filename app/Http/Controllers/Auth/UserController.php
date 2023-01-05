<?php

namespace App\Http\Controllers\Auth;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CreateUserRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function create(Role $role): View
    {
        return view('auth.register');
    }

    public function store(CreateUserRequest $request, Role $role): RedirectResponse
    {
        /** @var User $user */
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($role);
        $user->syncPermissions(
            collect(RoleEnum::tryFrom($role->name)
                ->defaultPermissions())
                ->map(fn (PermissionEnum $enum) => $enum->value)
        );

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
