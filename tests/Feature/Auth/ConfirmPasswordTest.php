<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfirmPasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_cant_proceed_without_password_confirmation()
    {
        $user = factory(User::class)->states('unconfirmed_password')->create();

        $this->assertNull($user->password_confirmed_at);

        $this->actingAs($user, 'api')
            ->getJson(route('settings'))
            ->assertStatus(423)
            ->assertJsonStructure(['message']);
    }

    /** @test */
    public function user_cant_proceed_with_expired_password_confirmation()
    {
        $user = factory(User::class)->states('expired_password_confirmation')->create();

        $this->assertNotNull($user->password_confirmed_at);

        $this->actingAs($user, 'api')
            ->getJson(route('settings'))
            ->assertStatus(423)
            ->assertJsonStructure(['message']);
    }

    /** @test */
    public function user_can_proceed_with_confirmed_password()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->getJson(route('settings'))
            ->assertSuccessful();
    }

    /** @test */
    public function user_can_confirm_password()
    {
        $user = factory(User::class)->states('unconfirmed_password')->create();

        $this->assertNull($user->password_confirmed_at);

        $this->actingAs($user, 'api')
            ->postJson(route('password.confirm'), [
                'password' => 'password',
            ])->assertSuccessful()
            ->assertJson(['confirmed' => true, 'user' => $user->toArray()]);

        $this->assertNotNull($user->password_confirmed_at);
    }

    /** @test */
    public function password_is_required()
    {
        $user = factory(User::class)->states('unconfirmed_password')->create();

        $this->assertNull($user->password_confirmed_at);

        $this->actingAs($user, 'api')
            ->postJson(route('password.confirm'))
            ->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function valid_password_should_be_provided()
    {
        $user = factory(User::class)->states('unconfirmed_password')->create();

        $this->assertNull($user->password_confirmed_at);

        $this->actingAs($user, 'api')
            ->postJson(route('password.confirm'), [
                'password' => 'wrong',
            ])->assertJsonValidationErrors(['password']);
    }
}
