<?php

namespace Tests\Browser\Subscription;

use App\Plan;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateSubscriptionTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $plans;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plans = Plan::all();
    }

    /** @test */
    public function user_can_create_subscription()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer();

        $user->updateDefaultPaymentMethod('pm_card_visa');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/settings/subscription')
                ->waitForText($this->plans->first()->nickname, 10)
                ->assertSee($this->plans->first()->nickname)
                ->waitForText($this->plans->last()->nickname, 10)
                ->assertSee($this->plans->last()->nickname)
                ->radio('plan', $this->plans->first()->id)
                ->press('Update')
                ->waitForText('Subscription updated successfully!', 10)
                ->assertSee('Subscription updated successfully!')
                ->signOut();
        });
    }
}
