<?php

namespace Tests\Browser\Subscription;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AddCardTest extends DuskTestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test */
    public function user_can_add_a_card()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer();

        $expire = now()->addYear();

        $this->browse(function (Browser $browser) use ($user, $expire) {
            $browser->actingAs($user)
                ->visit('/settings/subscription')
                ->waitForLocation('/settings/subscription')
                ->waitForText('No credit or debit cards.', 10)
                ->assertSee('No credit or debit cards.')
                ->press('Add Card')
                ->waitFor('#card-element iframe')
                ->withinFrame('#card-element iframe', function ($iframe) use ($expire) {
                    $iframe->waitfor('input[name="cardnumber"]')
                        ->type('cardnumber', '4242424242424242')
                        ->type('exp-date', $expire->format('my'))
                        ->type('cvc', '111')
                        ->type('postal', $this->faker->postcode);
                })->click('button[type="submit"]')
                ->waitForText('Credit card successfully added!', 10)
                ->assertSee('Credit card successfully added!')
                ->assertSee('4242')
                ->assertSee($expire->format('n/Y'))
                ->signOut($user);
        });
    }
}
