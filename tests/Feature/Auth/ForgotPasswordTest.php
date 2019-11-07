<?php

namespace Tests\Feature\Auth;

use App\Notifications\ResetPassword;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_send_password_reset_link()
    {
        $user = factory(User::class)->create();

        Notification::fake();

        $this->postJson(route('password.email'), [
            'email' => $user->email,
        ])->assertJsonStructure(['status']);

        $reset = DB::table('password_resets')->first();

        $this->assertNotNull($reset);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification, $channels) use ($reset) {
            return Hash::check($notification->token, $reset->token);
        });
    }

    /** @test */
    public function user_required_to_reset_password_email()
    {
        $this->postJson(route('password.email'), [
            'email' => 'missing@example.com',
        ])->assertJsonValidationErrors('email');
    }

    /** @test */
    public function email_is_required_to_send_password_reset_link()
    {
        $this->postJson(route('password.email'))
            ->assertJsonValidationErrors('email');
    }

    /** @test */
    public function email_should_be_valid()
    {
        $this->postJson(route('password.email'), [
            'email' => 'invalid',
        ])->assertJsonValidationErrors(['email']);
    }
}
