<?php

namespace Tests\Browser;

use App\Plan;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ResumeSubscriptionTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $plans;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plans = Plan::all();
    }

    /** @test */
    public function user_can_resume_canceled_subscription()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer();

        $paymentMethod = $user->updateDefaultPaymentMethod('pm_card_visa');

        $user->newSubscription($this->plans->first()->id)
            ->create($paymentMethod->id);

        $user->fresh()->subscription()->cancel();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/settings/subscription')
                ->waitForText($this->plans->first()->nickname, 10)
                ->assertSee($this->plans->first()->nickname)
                ->waitForText('You Are On A Grace Period')
                ->assertSee('You Are On A Grace Period')
                ->waitForText('Resume', 10)
                ->press('Resume')
                ->waitForText('Subscription updated successfully!', 10)
                ->assertSee('Subscription updated successfully!')
                ->signOut();
        });
    }
}
