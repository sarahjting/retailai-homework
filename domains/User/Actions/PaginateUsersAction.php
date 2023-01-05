<?php

namespace Domains\User\Actions;

use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;

class PaginateUsersAction
{
    public function execute(string $orderBy = 'email'): Paginator
    {
        if (!in_array($orderBy, ['name', 'email', 'created_at'])) {
            $orderBy = 'email';
        }

        return User::orderBy($orderBy)->orderBy('id')->paginate(8);
    }
}
