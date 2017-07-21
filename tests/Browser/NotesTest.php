<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use Tests\Browser\Pages\SignUpPage;
use Tests\Browser\Pages\NotesPage;

const DELAY = 500;

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

    public function test_user_can_save_new_note()
    {
        $user = factory(User::class)->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit(new NotesPage)
                ->enterNote('First', 'BodyFirst')
                ->saveNote()
                ->pause(DELAY)
                ->assertSeeIn('.alert', 'Your new note')
                ->assertSeeIn('.notes', 'You have 1 note')
                ->assertSeeIn('.notes', 'First')
                ->assertInputValue('#title', 'First')
                ->assertInputValue('#body', 'BodyFirst');
        });
    }

    public function test_user_can_see_word_count()
    {
        $user = factory(User::class)->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit(new NotesPage)
                ->enterNote('First', 'BodyFirst')
                ->assertSee('Word count: 1');
        });
    }

    public function test_user_can_start_new_note()
    {
        $user = factory(User::class)->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit(new NotesPage)
                ->enterNote('First', 'BodyFirst')
                ->saveNote()
                ->pause(DELAY)
                ->clickLink('Create new note')
                ->pause(DELAY)
                ->assertSee('A fresh note')
                ->assertInputValue('#title', '')
                ->assertInputValue('#body', '');
        });
    }
}
