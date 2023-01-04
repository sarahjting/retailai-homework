<?php

namespace Tests\Feature\Auth\UserController;

use App\Providers\RouteServiceProvider;
use Database\Factories\UserFactory;
use Tests\DatabaseTestCase;

class CreateTest extends DatabaseTestCase
{
    public function test_merchant_registration_screen_can_be_rendered(): void
    {
        $this->get('/merchant/signup')->assertOk();
    }

    public function test_admin_registration_screen_can_be_rendered(): void
    {
        $this->get('/admin/signup')->assertOk();
    }

    public function test_fails_on_superadmin(): void
    {
        $this->get('/superadmin/signup')->assertNotFound();
    }

    public function test_fails_on_nonsense(): void
    {
        $this->get('/foo/signup')->assertNotFound();
    }

    public function test_redirects_on_authenticated(): void
    {
        $this->actingAs(UserFactory::new()->create());
        $response = $this->get('/merchant/signup');
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
