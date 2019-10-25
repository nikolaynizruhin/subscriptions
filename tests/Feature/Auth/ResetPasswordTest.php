<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_reset_password()
    {
        $user = factory(User::class)->create();

        $this->postJson(route('password.update'), [
            'token' => Password::createToken($user),
            'email' => $user->email,
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ])->assertJson(['token' => $user->api_token])
            ->assertJsonStructure(['status', 'token'])
            ->assertSuccessful();

        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('new_password', $user->fresh()->password));
    }

    /** @test */
    public function guest_cant_reset_password()
    {
        $this->postJson(route('password.update'), [
            'token' => 'token',
            'email' => 'missing@example.com',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ])->assertJsonValidationErrors('email');

        $this->assertGuest();
    }

    /** @test */
    public function email_is_required_to_reset_password()
    {
        $this->postJson(route('password.update'))
            ->assertJsonValidationErrors('email');
    }

    /** @test */
    public function email_should_be_valid()
    {
        $user = factory(User::class)->create();

        $this->postJson(route('password.update'), [
            'token' => Password::createToken($user),
            'email' => 'invalid',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ])->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function password_is_required_to_reset_password()
    {
        $this->postJson(route('register'))
            ->assertJsonValidationErrors('password');
    }

    /** @test */
    public function password_should_has_min_length_8()
    {
        $user = factory(User::class)->create();

        $this->postJson(route('password.update'), [
            'token' => Password::createToken($user),
            'email' => 'invalid',
            'password' => 'less',
            'password_confirmation' => 'less',
        ])->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function token_is_required_to_reset_password()
    {
        $this->postJson(route('password.update'))
            ->assertJsonValidationErrors('token');
    }
}
