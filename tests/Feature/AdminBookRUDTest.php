<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;

use function PHPUnit\Framework\assertEquals;

class AdminBookRUDTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Tests if a book can be created
     *
     * @return void
     */
    public function test_all_books_can_be_retrieved()  
    {
        $this->withoutExceptionHandling();
        $book = Book::Create([
            'title' => 'Test Title',
            'author' => 'Test Author',
            'isbn' => strval(rand()),
            'price' => 13.13,
            'quantity' => 13
    
        ]);
        $book2 = Book::Create([
            'title' => 'Test Title2',
            'author' => 'Test Author2',
            'isbn' => strval(rand()),
            'price' => 13.13,
            'quantity' => 13
    
        ]);
        $response = $this->get('api/books');
        $response->assertJsonFragment(['title' => 'Test Title']);
        $response->assertJsonFragment(['title' => 'Test Title2']);
        $response->assertStatus(200);
    }

    public function test_a_book_can_be_retrieved()  
    {
        $this->withoutExceptionHandling();
        $book = Book::Create([
            'title' => 'Test Title get request',
            'author' => 'Test Author',
            'isbn' => strval(rand()),
            'price' => 13.13,
            'quantity' => 13
    
        ]);
       
        $response = $this->get('api/books/'.strval($book->id));
        // $json_string = json_encode($response, JSON_PRETTY_PRINT);
        // print($json_string);
        $response->assertJsonFragment(['title' => 'Test Title get request']);
        // $response->assertJsonFragment(['title' => 'Test Title2']);
        $response->assertStatus(200);
    }

    
    public function test_a_book_can_be_updated()  
    {
        $this->withoutExceptionHandling();
        $book = Book::Create([
            'title' => 'Test Title get request',
            'author' => 'Test Author',
            'isbn' => strval(rand()),
            'price' => 13.13,
            'quantity' => 13
    
        ]);
       
        $response = $this->patch('api/books/'.strval($book->id),
        [
            'title' => 'Test Title after update request',
            'author' => 'Test Author updated',
            'isbn' => strval(rand()),
            'price' => 13.14,
            'quantity' => 14
    
        ]);
        // $response->assertStatus(200);
        $new_book = Book::find($book->id);
        // $response->assertJsonFragment(['title' => 'Test Title get request']);
        // $response->assertJsonFragment(['title' => 'Test Title2']);
        assertEquals('Test Title after update request',$new_book->title);
        $response->assertRedirect('api/books/'.$new_book->id);
    }

    public function test_a_book_can_be_deleted()  
    {
        $this->withoutExceptionHandling();
        $book = Book::Create([
            'title' => 'Test Title get request to be deleted',
            'author' => 'Test Author',
            'isbn' => strval(rand()),
            'price' => 13.13,
            'quantity' => 13
    
        ]);
       
        $this->assertCount(1,Book::all());
        $response = $this->delete('api/books/'.strval($book->id));
        $this->assertCount(0,Book::all());
        $response->assertJsonMissing(['title' => 'Test Title get request to be deleted']);
    }
}

