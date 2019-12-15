<?php

namespace Tests\Feature\Auth;

use App\Http\Resources\User as UserResource;
use App\Notifications\VerifyEmail;
use App\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class VerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_should_receive_verification_email_after_registration()
    {
        Notification::fake();

        $this->postJson(route('register'), [
            'name' => 'John Doe',
            'email' => $email = 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSuccessful();

        $user = User::whereEmail($email)->first();

        $this->assertNull($user->email_verified_at);

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /** @test */
    public function user_can_verify_email()
    {
        $user = factory(User::class)->states('unverified')->create();

        Event::fake();

        $this->assertNull($user->email_verified_at);

        $this->actingAs($user, 'api')
            ->getJson($this->verificationUrl($user))
            ->assertSuccessful()
            ->assertJson([
                'verified' => true,
                'user' => (new UserResource($user))->jsonSerialize(),
            ]);

        $this->assertNotNull($user->email_verified_at);

        Event::assertDispatched(Verified::class, function ($event) use ($user) {
            return $event->user->is($user);
        });
    }

    /** @test */
    public function it_should_throw_authorization_exception_on_wrong_user_verification()
    {
        $user = factory(User::class)->states('unverified')->create();

        Event::fake();

        $this->actingAs($user, 'api')
            ->getJson(route('verification.verify', [
                'id' => 'wrong-id',
                'hash' => sha1($user->getEmailForVerification()),
            ]))->assertForbidden();

        $this->assertNull($user->email_verified_at);

        Event::assertNotDispatched(Verified::class);
    }

    /** @test */
    public function it_should_throw_authorization_exception_on_wrong_hash_verification()
    {
        $user = factory(User::class)->states('unverified')->create();

        Event::fake();

        $this->actingAs($user, 'api')
            ->getJson(route('verification.verify', [
                'id' => $user->getKey(),
                'hash' => 'wrong-hash',
            ]))->assertForbidden();

        $this->assertNull($user->email_verified_at);

        Event::assertNotDispatched(Verified::class);
    }

    /** @test */
    public function verified_user_cant_verify()
    {
        $user = factory(User::class)->create();

        Event::fake();

        $this->assertTrue($user->hasVerifiedEmail());

        $this->actingAs($user, 'api')
            ->getJson($this->verificationUrl($user))
            ->assertSuccessful()
            ->assertNoContent();

        Event::assertNotDispatched(Verified::class);
    }

    /** @test */
    public function guest_cant_verify_email()
    {
        $this->getJson(route('verification.verify', [
            'id' => 'id',
            'hash' => 'hash',
        ]))->assertUnauthorized();
    }

    /** @test */
    public function guest_cant_resend_verification_email()
    {
        $this->postJson(route('verification.resend'))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_resend_verification_email()
    {
        $user = factory(User::class)->states('unverified')->create();

        Notification::fake();

        $this->actingAs($user, 'api')
            ->postJson(route('verification.resend'))
            ->assertJson(['resent' => true]);

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /** @test */
    public function verified_user_should_not_be_able_to_resend_verification_email()
    {
        $user = factory(User::class)->create();

        Notification::fake();

        $this->actingAs($user, 'api')
            ->postJson(route('verification.resend'))
            ->assertNoContent();

        Notification::assertNotSentTo($user, VerifyEmail::class);
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param MustVerifyEmail $user
     * @return string
     */
    protected function verificationUrl(MustVerifyEmail $user)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );
    }
}
