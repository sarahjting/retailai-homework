<?php

namespace Tests\Util;

use App\Enums\RoleEnum;
use Closure;
use Database\Factories\UserFactory;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;

class AuthorizationCase
{
    private bool $isGuest = true;
    private array $roles = [];
    private array $permissions = [];

    public function assert(Closure $responseAssertion): array
    {
        return [
            'userFactory' => $this->isGuest ? null : UserFactory::new()->permissions($this->permissions)->roles($this->roles),
            'asserts' => $responseAssertion,
        ];
    }

    public function unauthenticated(): array
    {
        return $this->assert(fn (TestResponse $res) => $res->assertStatus(Response::HTTP_UNAUTHORIZED));
    }

    public function redirectToMerchantLogin(): array
    {
        return $this->assert(fn (TestResponse $res) => $res
            ->assertRedirect(route('login', ['role' => RoleEnum::MERCHANT->value])));
    }

    public function forbidden(): array
    {
        return $this->assert(fn (TestResponse $res) => $res->assertStatus(Response::HTTP_FORBIDDEN));
    }

    public function ok(): array
    {
        return $this->assert(fn (TestResponse $res) => $res->assertStatus(Response::HTTP_OK));
    }

    public function guest(): AuthorizationCase
    {
        $this->isGuest = true;
        return $this;
    }

    public function merchant(): AuthorizationCase
    {
        $this->isGuest = false;
        $this->roles = [RoleEnum::MERCHANT->value];
        return $this;
    }

    public function admin(): AuthorizationCase
    {
        $this->isGuest = false;
        $this->roles = [RoleEnum::ADMIN->value];
        return $this;
    }

    public function superadmin(): AuthorizationCase
    {
        $this->isGuest = false;
        $this->roles = [RoleEnum::SUPERADMIN->value];
        return $this;
    }

    public function permissions(array $permissions): AuthorizationCase
    {
        $this->isGuest = false;
        $this->permissions = $permissions;
        return $this;
    }
}
