<?php

namespace Tests\Feature\Auth\AuthenticatedSessionController;

use App\Providers\RouteServiceProvider;
use Database\Factories\UserFactory;
use Tests\DatabaseTestCase;

class StoreTest extends DatabaseTestCase
{
    /**
     * @dataProvider provider_users_authenticate_using_proper_screen
     */
    public function test_users_authenticate_using_proper_screen(UserFactory $userFactory, string $endpoint, bool $succeeds): void
    {
        $user = $userFactory->create();

        $response = $this->post($endpoint, [
            'email' => $user->email,
            'password' => 'password',
        ]);

        if ($succeeds) {
            $response->assertRedirect(RouteServiceProvider::HOME);
            $this->assertAuthenticated();
        } else {
            $response->assertSessionHasErrors(['email']);
            $this->assertGuest();
        }
    }

    public function provider_users_authenticate_using_proper_screen(): array
    {
        $merchantFactory = UserFactory::new()->merchant();
        $adminFactory = UserFactory::new()->admin();
        $superAdminFactory = UserFactory::new()->superadmin();
        $merchantLogin = '/merchant/signin';
        $adminLogin = '/admin/signin';
        return [
            'merchant logs in using merchant screen' => [$merchantFactory, $merchantLogin, true],
            'merchant logs in using admin screen' => [$merchantFactory, $adminLogin, false],
            'admin logs in using merchant screen' => [$adminFactory, $merchantLogin, false],
            'admin logs in using admin screen' => [$adminFactory, $adminLogin, true],
            'superadmin logs in using merchant screen' => [$superAdminFactory, $merchantLogin, true],
            'superadmin logs in using admin screen' => [$superAdminFactory, $adminLogin, true],
        ];
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = UserFactory::new()->merchant()->create();

        $this->post('/merchant/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
