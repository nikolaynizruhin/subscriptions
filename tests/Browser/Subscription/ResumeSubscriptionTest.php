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

    /** @test */
    public function user_can_resume_canceled_subscription()
    {
        $user = factory(User::class)->create();

        [$pro, $basic] = Plan::all();

        $user->createAsStripeCustomer();

        $paymentMethod = $user->updateDefaultPaymentMethod('pm_card_visa');

        $user->newSubscription($pro->id)->create($paymentMethod->id);

        $user->fresh()->subscription()->cancel();

        $this->browse(function (Browser $browser) use ($user, $pro, $basic) {
            $browser->actingAs($user)
                ->visit('/settings/subscription')
                ->waitForText($pro->nickname, 10)
                ->waitForText($basic->nickname, 10)
                ->waitForText('You Are On A Grace Period')
                ->assertSee('You Are On A Grace Period')
                ->press('Resume')
                ->waitForText('Subscription updated successfully!', 10)
                ->assertSee('Subscription updated successfully!')
                ->signOut();
        });
    }
}
