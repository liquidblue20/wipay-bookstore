<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderStatus;

use function PHPUnit\Framework\assertEquals;

class PaymentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Tests if all books can be retrieved at once from the database
     *
     * @return void
     */
    public function test_a_order_can_be_initiated()  
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
        $user = User::create(['name' => 'userTest',
        'email' => 'tester@yahoo.com',
        'password' => '123321'
        ]);
        $statuses = [
            ['name' => 'pending'],            
            ['name' => 'error'],
            ['name' => 'complete'],
            ['name' => 'refund']
        ];
        foreach ($statuses as $status) {
            OrderStatus::create($status);
    }

        $request = [
        'book_id' => $book->id,
        'quantity'=> $book->quantity,
        'user_id' => $user->id
        ];
        
        $response = $this->post('api/payment',$request);
        $response->assertJsonFragment(['message' => 'OK']);   //tests for the existence of the 2 books just created
        $this->assertCount(1,Order::all());
        $response->assertStatus(200);
    }

    /**
     * Tests if a book can be retrieved from the database
     *
     * @return void
     */
    public function test_a_book_can_be_out_of_stock_at_order_attempt()  
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
        $user = User::create(['name' => 'userTest',
        'email' => 'tester@yahoo.com',
        'password' => '123321'
        ]);
        $statuses = [
            ['name' => 'pending'],            
            ['name' => 'error'],
            ['name' => 'complete'],
            ['name' => 'refund']
        ];
        foreach ($statuses as $status) {
            OrderStatus::create($status);
    }

        $request = [
        'book_id' => $book->id,
        'quantity'=> $book->quantity+10,
        'user_id' => $user->id
        ];
        
        $response = $this->post('api/payment',$request);
        $response->assertJsonFragment(['message' => 'Not enough stock book of the requested book at this time']);   //tests for the existence of the 2 books just created
        $this->assertCount(0,Order::all());
        $response->assertStatus(200);
    }

    /**
     * Tests if a book can be updated in the database
     *
     * @return void
     */
    // public function test_a_book_can_be_out_of_stock_after_order_process_started()  
    // {
    //     //disable built in exception handling
    //     $this->withoutExceptionHandling();  //useful in getting more detailed errors from the console for certain errors
    //     $book = Book::Create([
    //         'title' => 'Test Title',
    //         'author' => 'Test Author',
    //         'isbn' => strval(rand()),
    //         'price' => 13.13,
    //         'quantity' => 13
    
    //     ]);
    //     $user = User::create(['name' => 'userTest',
    //     'email' => 'tester@yahoo.com',
    //     'password' => '123321'
    //     ]);
    //     $statuses = [
    //         ['name' => 'pending'],            
    //         ['name' => 'error'],
    //         ['name' => 'complete'],
    //         ['name' => 'refund']
    //     ];
    //     foreach ($statuses as $status) {
    //         OrderStatus::create($status);
    // }

    //     $request = [
    //     'book_id' => $book->id,
    //     'quantity'=> $book->quantity,
    //     'user_id' => $user->id
    //     ];
        
    //     $book->update(['quantity' => 1]);
    //     $book->save();
    //     $response = $this->post('api/payment',$request);
    //     $response->assertJsonFragment(['message' => 'Not enough stock book of the requested book at this time']);   //tests for the existence of the 2 books just created
    //     $this->assertCount(1,Order::all());
    //     $response->assertStatus(200);
    //     $resp = $response->json();
    //     //Fill out form for testing purposes
        
    // }

   
}

