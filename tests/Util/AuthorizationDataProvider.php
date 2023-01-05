<?php

namespace Tests\Util;

use Closure;

class AuthorizationDataProvider
{
    public function __invoke(array $cases) {
        return collect($cases)
            ->mapWithKeys(fn (Closure $fn, $key) => [
                $key => $fn(new AuthorizationCase()),
            ])->toArray();
    }
}
