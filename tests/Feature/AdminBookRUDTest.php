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
     * Tests if all books can be retrieved at once from the database
     *
     * @return void
     */
    public function test_all_books_can_be_retrieved()  
    {
        //disable built in exception handling
        $this->withoutExceptionHandling();  //useful in getting more detailed errors from the console for certain errors
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
        $response->assertJsonFragment(['title' => $book->title]);   //tests for the existence of the 2 books just created
        $response->assertJsonFragment(['title' => $book2->title]);
        $response->assertStatus(200);
    }

    /**
     * Tests if a book can be retrieved from the database
     *
     * @return void
     */
    public function test_a_book_can_be_retrieved()  
    {
        //disable built in exception handling
        $this->withoutExceptionHandling();
        $book = Book::Create([
            'title' => 'Test Title get request',
            'author' => 'Test Author',
            'isbn' => strval(rand()),
            'price' => 13.13,
            'quantity' => 13
    
        ]);
        $response = $this->get('api/books/'.strval($book->id));
        $response->assertJsonFragment(['title' => $book->title]);
        $response->assertStatus(200);
    }

    /**
     * Tests if a book can be updated in the database
     *
     * @return void
     */
    public function test_a_book_can_be_updated()  
    {
        //disable built in exception handling
        $this->withoutExceptionHandling();

        //create book to be updated
        $book = Book::Create([
            'title' => 'Test Title get request',
            'author' => 'Test Author',
            'isbn' => strval(rand()),
            'price' => 13.13,
            'quantity' => 13
        ]);

        //attempt to update book just created via api endpoint
        $response = $this->patch('api/books/'.strval($book->id),
        [
            'title' => 'Test Title after update request',
            'author' => 'Test Author updated',
            'isbn' => strval(rand()),
            'price' => 13.14,
            'quantity' => 14
    
        ]);

        //Find back book via method to see if the changes were persistent
        $updated_book = Book::find($book->id);
        //Compare old value to the supposedly changed value,if they are equal, the method works
        assertEquals('Test Title after update request',$updated_book->title);
        //Update route should redirect to show the newly updated models attributes
        $response->assertRedirect('api/books/'.$updated_book->id);
    }

    /**
     * Tests if a book can be deleted from the database
     *
     * @return void
     */
    public function test_a_book_can_be_deleted()  
    {
        //disable built in exception handling
        $this->withoutExceptionHandling();
        $book = Book::Create([
            'title' => 'Test Title get request to be deleted',
            'author' => 'Test Author',
            'isbn' => strval(rand()),
            'price' => 13.13,
            'quantity' => 13
    
        ]);
       
        //Checks if book was created, then deletes and recounts to make sure count is consistent with deletion
        $this->assertCount(1,Book::all());
        $response = $this->delete('api/books/'.$book->id);
        $this->assertCount(0,Book::all());
        $response->assertJsonMissing(['title' => 'Test Title get request to be deleted']);
    }
}

