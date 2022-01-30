<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

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

        Sanctum::actingAs(
            User::factory()->create(),
            ['crud:book']
        );

        $bookcount = Book::All()->count();
        $response =$this->withHeaders(['Accept'=>'application/json'])->post('api/books',
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
        Sanctum::actingAs(
            User::factory()->create(),
            ['crud:book']
        );
    $response =$this->withHeaders(['Accept'=>'application/json'])->post('api/books',
    [
        'title' => '',
        'author' => 'Test Author',
        'isbn' => strval(rand()),
        'price' => 13.13,
        'quantity' => 13

    ]);
    $response->assertJsonValidationErrors('title');
    }

    /**
     * Tests if an author requirement is being enforced on book creation
     **/
    public function test_an_author_is_required()  //php unit was configured for tests to be prefixed with test
    {
        // $this->withoutExceptionHandling();
        Sanctum::actingAs(
            User::factory()->create(),
            ['crud:book']
        );
        $response = $this->withHeaders(['Accept'=>'application/json'])->post('api/books',
    [
        'title' => 'Test Title',
        'author' => '',
        'isbn' => 'd13d3d3',
        'price' => 13.13,
        'quantity' => 13

    ]);
    $response->assertJsonValidationErrors('author');
    }

    /**
     * Tests if a isbn requirement is being enforced on book creation
     **/
    public function test_an_isbn_is_required()  //php unit was configured for tests to be prefixed with test
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['crud:book']
        );
        $response = $this->withHeaders(['Accept'=>'application/json'])->post('api/books',
    [
        'title' => 'Test Title',
        'author' => 'Test Author',
        'isbn' => '',
        'price' => 13.13,
        'quantity' => 13

    ]);
    $response->assertJsonValidationErrors('isbn');
    }
}
