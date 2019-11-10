<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_reset_password_confirmation_timeout()
    {
        $user = factory(User::class)->states('unconfirmed_password')->create();

        $this->assertNull($user->password_confirmed_at);

        $user->resetPasswordConfirmationTimeout();

        $this->assertNotNull($user->password_confirmed_at);
    }
}
