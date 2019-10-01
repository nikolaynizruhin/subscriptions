<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login()
    {
        $user = factory(User::class)->create();

        $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertJson([
            'token' => $user->api_token,
        ])->assertSuccessful();

        $this->assertNotNull($user->api_token);
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function email_is_required_to_login()
    {
        $this->postJson(route('login'))
            ->assertJsonValidationErrors('email');
    }

    /** @test */
    public function password_is_required_to_login()
    {
        $this->postJson(route('login'))
            ->assertJsonValidationErrors('password');
    }

    /** @test */
    public function user_cant_login_with_wrong_credentials()
    {
        $this->postJson(route('login'), [
            'email' => 'wrong@email.com',
            'password' => 'wrong',
        ])->assertJsonValidationErrors('email');

        $this->assertGuest();
    }

    /** @test */
    public function user_can_logout()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->postJson(route('logout'))
            ->assertSuccessful();

        $this->assertGuest();
    }
}