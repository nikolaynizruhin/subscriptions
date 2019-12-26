<?php

namespace Tests\Browser\Subscription;

use App\Plan;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SwapSubscriptionTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $plans;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plans = Plan::all();
    }

    /** @test */
    public function user_can_swap_subscription_plan()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer();

        $paymentMethod = $user->updateDefaultPaymentMethod('pm_card_visa');

        $user->newSubscription($this->plans->first()->id)
            ->create($paymentMethod->id);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/settings/subscription')
                ->waitForText($this->plans->first()->nickname, 10)
                ->assertSee($this->plans->first()->nickname)
                ->waitForText($this->plans->last()->nickname, 10)
                ->assertSee($this->plans->last()->nickname)
                ->assertRadioSelected('plan', $this->plans[0]->id)
                ->radio('plan', $this->plans->last()->id)
                ->press('Update')
                ->waitForText('Subscription updated successfully!', 10)
                ->assertSee('Subscription updated successfully!')
                ->signOut();
        });
    }
}
