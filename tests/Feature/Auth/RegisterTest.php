<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_register()
    {
        $response = $this->postJson(route('register'), [
            'name' => 'John Doe',
            'email' => $email = 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::whereEmail($email)->first();

        $response
            ->assertJson(['token' => $user->api_token])
            ->assertSuccessful();

        $this->assertNotNull($user->api_token);
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('users', $user->toArray());
    }

    /** @test */
    public function name_is_required_to_register()
    {
        $this->postJson(route('register'))
            ->assertJsonValidationErrors('name');
    }

    /** @test */
    public function email_is_required_to_register()
    {
        $this->postJson(route('register'))
            ->assertJsonValidationErrors('email');
    }

    /** @test */
    public function password_is_required_to_register()
    {
        $this->postJson(route('register'))
            ->assertJsonValidationErrors('password');
    }

    /** @test */
    public function email_should_be_valid()
    {
        $this->postJson(route('register'), [
            'name' => 'John Doe',
            'email' => 'invalid',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function passwords_should_match()
    {
        $this->postJson(route('register'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'mismatch',
        ])->assertJsonValidationErrors(['password']);
    }
}
