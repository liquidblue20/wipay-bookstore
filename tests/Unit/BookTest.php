<?php

namespace Tests\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Book;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_book_quantity_greater_or_equal_to()
    {
        $requested_quantity = 12;
        $newbook = Book::create([
            'title' => '',
            'author' => 'Test Author',
            'isbn' => strval(rand()),
            'price' => 13.13,
            'quantity' => 13
    
        ]);
        $this->assertTrue($newbook->is_sellable($requested_quantity));
        $requested_quantity = 14;
        $this->assertFalse($newbook->is_sellable($requested_quantity));
    }
}
