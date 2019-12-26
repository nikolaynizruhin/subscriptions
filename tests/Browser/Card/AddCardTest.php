<?php

namespace Tests\Browser\Card;

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
                ->waitForText('No credit or debit cards.', 10)
                ->assertSee('No credit or debit cards.')
                ->press('Add Card')
                ->waitFor('#card-element iframe')
                ->withinFrame('#card-element iframe', function ($iframe) use ($expire) {
                    $iframe->waitfor('input[name="cardnumber"]', 10)
                        ->type('cardnumber', '4242424242424242')
                        ->waitfor('input[name="exp-date"]')
                        ->type('exp-date', $expire->format('my'))
                        ->waitfor('input[name="cvc"]')
                        ->type('cvc', '111')
                        ->waitfor('input[name="postal"]')
                        ->type('postal', $this->faker->postcode);
                })->click('@add-card-button')
                ->waitForText('Credit card successfully added!', 10)
                ->assertSee('Credit card successfully added!')
                ->assertSee('4242')
                ->assertSee($expire->format('n/Y'))
                ->signOut();
        });
    }
}
