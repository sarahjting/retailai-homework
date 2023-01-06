<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserPermissionsRequest;
use App\Models\User;
use Domains\User\Actions\PaginateUsersAction;
use Domains\User\Actions\UpdateUserPermissionsAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserPermissionsController extends Controller
{
    public function index(Request $request, PaginateUsersAction $paginateUsersAction): View
    {
        return view('pages.users.permissions.index', [
            'users' => $paginateUsersAction->execute(
                orderBy: $request->get('sort_by', 'email'),
            ),
        ]);
    }

    public function edit(User $user): View
    {
        return view('pages.users.permissions.edit', ['user' => $user]);
    }

    public function update(UpdateUserPermissionsRequest $request, User $user, UpdateUserPermissionsAction $updateUserPermissionsAction): RedirectResponse
    {
        $updateUserPermissionsAction->execute(
            user: $user,
            roles: $request->get('roles', []),
            permissions: $request->get('permissions', []),
        );
        return redirect()->to(route('user_permissions.edit', ['user' => $user]));
    }
}
