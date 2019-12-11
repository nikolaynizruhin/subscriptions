<?php

namespace Tests\Browser\Settings;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UpdatePasswordTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_update_own_password()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/settings/security')
                ->type('password', 'password')
                ->type('new_password', $newPassword = 'new_password')
                ->type('new_password_confirmation', $newPassword)
                ->press('Update')
                ->waitForText('Password updated successfully!')
                ->assertSee('Password updated successfully!')
                ->signOut();
        });
    }
}
