<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use Tests\Browser\Pages\SignUpPage;

class SignUpTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * Sign up test
     *
     * @return void
     */
    public function test_user_can_sign_up()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new SignUpPage)
                ->signUp('begimov', 'begimov@gmail.com', '123456', '123456')
                ->assertPathIs('/home')
                ->assertSeeIn('.navbar', 'begimov');
        });
    }
}
