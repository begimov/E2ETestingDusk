<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Note;
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

    public function test_note_saved_when_starting_new_note()
    {
        $user = factory(User::class)->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit(new NotesPage)
                ->enterNote('First', 'BodyFirst')
                ->saveNote()
                ->pause(DELAY)
                ->type('#title', 'First update')
                ->type('#body', 'BodyFirst update')
                ->clickLink('Create new note')
                ->pause(DELAY)
                ->assertSeeIn('.notes', 'First update')
                ->clickLink('First update')
                ->pause(DELAY)
                ->assertInputValue('#title', 'First update')
                ->assertInputValue('#body', 'BodyFirst update');
        });
    }

    public function test_cant_save_not_without_title()
    {
        $user = factory(User::class)->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit(new NotesPage)
                ->saveNote()
                ->pause(DELAY)
                ->assertMissing('.alert')
                ->assertSeeIn('.notes', 'No notes')
                ->assertDontSeeIn('.notes', 'You have one note')
                ->assertMissing('.notes ul li:nth-child(2)')
                ->enterNote('', 'BodyFirst')
                ->saveNote()
                ->pause(DELAY)
                ->assertInputValue('#title', '')
                ->assertInputValue('#body', 'BodyFirst');
        });
    }

    public function test_open_existing_note()
    {
        $user = factory(User::class)->create();

        $note = factory(Note::class)->create([
            'user_id' => $user->id
        ]);

        $this->browse(function (Browser $browser) use ($user, $note) {
            $browser->loginAs($user)
                ->visit(new NotesPage)
                ->pause(DELAY)
                ->assertSee('You have 1 note')
                ->clickLink($note->title)
                ->pause(DELAY)
                ->assertInputValue('#title', $note->title)
                ->assertInputValue('#body', $note->body);
        });
    }
}
