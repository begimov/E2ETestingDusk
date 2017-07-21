<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use Tests\Browser\Pages\SignInPage;

class SignInTest extends DuskTestCase
{
    use DatabaseMigrations;
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

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new SignInPage)
                ->signIn($user->email, '123456')
                ->assertPathIs('/home')
                ->assertSeeIn('.navbar', 'begimov');
        });
    }
}
