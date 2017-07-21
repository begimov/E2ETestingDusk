<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

class SignInTest extends DuskTestCase
{
    /**
     * Sign In Test
     *
     * @return void
     */
    public function test_user_can_sign_in()
    {
        $user = factory(User::class)->create([
            'email' => 'begimov@gmail.co',
            'password' => bcrypt('123456'),
            'name' => 'begimov',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'begimov@gmail.co',
            'name' => 'begimov',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('#email', $user->email)
                ->type('#password', '123456')
                ->press('Login')
                ->assertPathIs('/home')
                ->assertSeeIn('.navbar', 'begimov');
        });
    }
}
