<?php

namespace Tests\Feature\Auth;

use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_register()
    {
        Carbon::setTestNow(now()->startOfDay());

        $response = $this->postJson(route('register'), [
            'name' => 'John Doe',
            'email' => $email = 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::whereEmail($email)->first();

        $response
            ->assertSuccessful()
            ->assertJson([
                'token' => $user->api_token,
                'user' => (new UserResource($user))->jsonSerialize(),
            ]);

        $this->assertNotNull($user->api_token);
        $this->assertNotNull($user->stripe_id);
        $this->assertNotNull($user->trial_ends_at);
        $this->assertEquals($user->trial_ends_at->diff(now())->days, config('subscription.trial_days'));
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('users', $user->getOriginal());
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

    /** @test */
    public function password_should_has_min_length_8()
    {
        $this->postJson(route('register'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'less',
            'password_confirmation' => 'less',
        ])->assertJsonValidationErrors(['password']);
    }
}
