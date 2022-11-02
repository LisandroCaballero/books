<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BooksTest extends TestCase
{
    use RefreshDatabase;

   /** @test  */
    public function can_get_all_books()
    {
        $book = Book::factory(4)->create();

        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $book[0]->title,
        ]);
    }

    /** @test  */
    public function can_get_one_books()
    {
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
            'title' => $book->title,
        ]);
    }

    /** @test */
    public function can_create_books()
    {
        $this->postJson(route('books.store'),[])
            ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'),[
            'title' => 'Clean Code'
        ])->assertJsonFragment([
            'title' => 'Clean Code'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Clean Code'
        ]);
    }

    /** @test */
    public function can_update_books()
    {
        $this->postJson(route('books.store'),[])
            ->assertJsonValidationErrorFor('title');

        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book),[
           'title' => 'Clean Code 2'
        ])->assertJsonFragment([
            'title' => 'Clean Code 2'
        ]);

        $this->assertDatabaseHas('books',[
           'title' => 'Clean Code 2'
        ]);
    }

    /**
     * @test
     */
    public function can_delete_books()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();

        $this->assertDatabaseCount('books',0);
    }
}
