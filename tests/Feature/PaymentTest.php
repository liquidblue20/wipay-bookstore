<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Database\Seeders\OrderStatusSeeder;


class PaymentTest extends TestCase
{
    
    use RefreshDatabase;



    /**
     * Tests if authentication needs to occur on payment request
     *
     * @return void
     */
    public function test_cannot_pay_unless_authenticated()  
    {
        //disable built in exception handling
        // $this->withoutExceptionHandling();  //useful in getting more detailed errors from the console for certain errors
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
        $response = $this->withHeaders(['Accept'=>'application/json'])->post('api/payment',['book_id' => 1,'quantity' => 7]);
        $response->assertJsonFragment(['message' => 'Unauthenticated.']);   //tests for the existence of the 2 books just created
        $response->assertStatus(401);
    }
    /**
     * Tests if orders can be started (get link to hosted merchant page)
     *
     * @return void
     */
    public function test_a_order_can_be_initiated()  
    {
        //disable built in exception handling
        // $this->withoutExceptionHandling();  //useful in getting more detailed errors from the console for certain errors

        //Create Book to order
        $book = Book::Create([
            'title' => 'Test Title',
            'author' => 'Test Author',
            'isbn' => strval(rand()),
            'price' => 13.13,
            'quantity' => 13
    
        ]);
       
        //Log in as a user that has the ability to purchase
        Sanctum::actingAs(
            User::factory()->create(),
            ['purchase:book']
        );

        //Creates order statuses required for procedure to execute
        $this->seed(OrderStatusSeeder::class);

        //Crafts requests sent to endpoint
        $request = [
        'book_id' => $book->id,
        'quantity'=> $book->quantity
        ];
        //Sends request for payment initialisation
        $response = $this->withHeaders(['Accept'=>'application/json'])->post('api/payment',$request);

        //Checks for JSON response OK
        $response->assertJsonFragment(['message' => 'OK']);   
        //Checks if an order was created
        $order = Order::where('book_id',$book->id)->firstOrFail();    //finding the one order in the database and testing for its details
        $this->assertEquals('pending',$order->orderStatus->name);   
        $this->assertEquals($book->id,$order->book->id);
        $response->assertStatus(200);
    }

    /**
     * Tests if a book can be out of stock upon order
     *
     * @return void
     */
    public function test_a_book_can_not_have_adequte_inventory_at_order_attempt()  
    {
        //disable built in exception handling
        // $this->withoutExceptionHandling();  //useful in getting more detailed errors from the console for certain errors
        $book = Book::Create([
            'title' => 'Test Title',
            'author' => 'Test Author',
            'isbn' => strval(rand()),
            'price' => 13.13,
            'quantity' => 13
    
        ]);
        //Creates user with necessary permissions to access endpoint for testing
        Sanctum::actingAs(
            User::factory()->create(),
            ['purchase:book']
        );

        //Needed for status of order to be assigned
        $this->seed(OrderStatusSeeder::class);

        $request = [
        'book_id' => $book->id,
        'quantity'=> $book->quantity+10 // requesting 10 more than is available in stock
        ];
        
        $response = $this->withHeaders(['Accept'=>'application/json'])->post('api/payment',$request);
        $response->assertJsonFragment(['message' => 'Not enough stock book of the requested book at this time']);   //tests for the existence of the 2 books just created
        $this->assertCount(3,Order::all()); //No order should be created
        $response->assertStatus(200);
    }
}

