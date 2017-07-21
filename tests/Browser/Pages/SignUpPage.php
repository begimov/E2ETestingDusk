<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class SignUpPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/register';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs('/register');
    }

    public function signUp(Browser $browser, $name = null,
                              $email = null, $password = null, $passConf = null)
    {
        $browser->type('@name', $name)
            ->type('@email', $email)
            ->type('@password', $password)
            ->type('@password-confirm', $passConf)
            ->press('Register');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@name' => '#name',
            '@email' => '#email',
            '@password' => 'input[name="password"]',
            '@password-confirm' => '#password-confirm',
        ];
    }
}
