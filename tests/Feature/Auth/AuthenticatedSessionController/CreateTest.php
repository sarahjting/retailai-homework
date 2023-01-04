<?php

namespace Tests\Feature\Auth\AuthenticatedSessionController;

use Tests\DatabaseTestCase;

class CreateTest extends DatabaseTestCase
{
    public function test_merchant_login_screen_can_be_rendered(): void
    {
        $this->get('/merchant/signin')->assertOk();
    }

    public function test_admin_login_screen_can_be_rendered(): void
    {
        $this->get('/admin/signin')->assertOk();
    }

    public function test_fails_superadmin_login_screen(): void
    {
        $this->get('/superadmin/signin')->assertNotFound();
    }

    public function test_fails_nonsense_login_screen(): void
    {
        $this->get('/foo/signin')->assertNotFound();
    }
}
