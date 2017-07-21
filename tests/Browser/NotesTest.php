<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use Tests\Browser\Pages\SignUpPage;

class NotesTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * No notes on first visit after signin up
     *
     * @return void
     */
    public function test_user_should_see_no_notes_on_very_first_visit()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new SignUpPage)
                ->signUp('begimov', 'begimov@gmail.com', '123456', '123456')
                ->visit('/home')
                ->assertSee('No notes yet')
                ->assertSee('Untitled')
                ->assertValue('#title', '')
                ->assertValue('#body', '');
        });
    }
}
