<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;


class BookCreationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Tests if a book can be created
     *
     * @return void
     */
    public function test_a_book_can_be_added()  //php unit was configured for tests to be prefixed with test
    {
        $this->withoutExceptionHandling();
        $bookcount = Book::All()->count();
        $response = $this->post('api/books',
    [
        'title' => 'Test Title',
        'author' => 'Test Author',
        'isbn' => strval(rand()),
        'price' => 13.13,
        'quantity' => 13

    ]);
    $this->assertCount($bookcount+1,Book::All());

    $response->assertStatus(201);
    }

    /**
     * Tests if a title requirement is being enforced on book creation
     **/
    public function test_a_title_is_required()  
    {
        $response = $this->post('api/books',
    [
        'title' => '',
        'author' => 'Test Author',
        'isbn' => strval(rand()),
        'price' => 13.13,
        'quantity' => 13

    ]);
    $response->assertSessionHasErrors('title');
    }

    /**
     * Tests if an author requirement is being enforced on book creation
     **/
    public function test_an_author_is_required()  //php unit was configured for tests to be prefixed with test
    {
        $response = $this->post('api/books',
    [
        'title' => 'Test Title',
        'author' => '',
        'isbn' => strval(rand()),
        'price' => 13.13,
        'quantity' => 13

    ]);
    $response->assertSessionHasErrors('author');
    }

    /**
     * Tests if a isbn requirement is being enforced on book creation
     **/
    public function test_an_isbn_is_required()  //php unit was configured for tests to be prefixed with test
    {
        $response = $this->post('api/books',
    [
        'title' => 'Test Title',
        'author' => 'Test Author',
        'isbn' => '',
        'price' => 13.13,
        'quantity' => 13

    ]);
    $response->assertSessionHasErrors('isbn');
    }
}
