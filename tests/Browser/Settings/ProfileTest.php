<?php

namespace Tests\Browser\Settings;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProfileTest extends DuskTestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test */
    public function user_can_update_own_profile()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/settings/profile')
                ->waitForLocation('/settings/profile')
                ->clear('name')
                ->type('name', $name = $this->faker->name)
                ->clear('email')
                ->type('email', $email = $this->faker->email)
                ->press('Update')
                ->waitForText('Profile updated successfully!')
                ->assertSee('Profile updated successfully!')
                ->assertInputValue('name', $name)
                ->assertInputValue('email', $email)
                ->signOut($user);
        });
    }
}
