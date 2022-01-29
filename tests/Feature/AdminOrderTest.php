<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use App\Models\Book;
use App\Models\OrderStatus;
use App\Models\User;

use function PHPUnit\Framework\assertEquals;

class AdminOrderTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Tests if all books can be retrieved at once from the database
     *
     * @return void
     */
    public function test_all_orders_can_be_retrieved()  
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
    $order_id = 'order123';
    $total = 10.11;
    Order::create(['book_id' => strval($book->id), 'quantity' => $book->quantity,
    'purchase_price' => $book->price,'total' => $total,'wipay_order_id' => $order_id,
    'order_status_id' => OrderStatus::where('name','pending')->first()->id,'user_id' =>$user->id]);

        $response = $this->get('api/orders');
        $response->assertJsonFragment(['wipay_order_id' => $order_id]);
        $response->assertStatus(200);
    }

    
   /**
     * Tests if all books can be retrieved at once from the database
     *
     * @return void
     */
    public function test_a_single_order_can_be_retrieved()  
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
    $order_id = 'order123';
    $total = 10.11;
    $order =Order::create(['book_id' => $book->id, 'quantity' => $book->quantity,
    'purchase_price' => $book->price,'total' => $total,'wipay_order_id' => $order_id,
    'order_status_id' => OrderStatus::where('name','pending')->first()->id,'user_id' =>$user->id]);

        $response = $this->get('api/orders/'.$order->id);
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $order->id]);
        $response->assertJsonFragment(['wipay_order_id' => $order->wipay_order_id]);
    }
}

