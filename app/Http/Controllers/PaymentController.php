<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    //Submits payment request to wipay to initialise payment process and return utl to user
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|numeric',
            'quantity' => 'required|numeric|gt:0'
        ]);
        // http://127.0.0.1:8001/api/payment_result
        // https://tt.wipayfinancial.com/response/
        $book = Book::find($request->book_id); //Pointless to lock into transaction here as the user will be taken to hosting page,
                                               //where we have to wait an certain period till we receive response at another api endpoint
        //Check if book is in inventory
        if ($book->is_sellable($request->quantity) == False)
        {
            return response()->json(["message" => 'Not enough stock book of the requested book at this time']);
        }

        //Calculates total to submit
        $total = $request->quantity * $book->price;
        $total = number_format((float)$total, 2, '.', '');

        // Variable initialisation required for WiPay API
        $account_number = 1234567890;
        $card_type = 'mastercard';
        $currency = 'TTD';
        $environment = 'sandbox';
        $fee_structure = 'customer_pay';
        $method = 'credit_card';
        $order_id = Str::substr((string) Str::uuid(),0,25);
        $origin= 'WiPay-example_app';
        $response_url= 'http://127.0.0.1:8000/api/payment_result';
        $country_code='TT';

        // Creates order associated with this transaction
        $order = Order::create(['book_id' => $book->id, 'quantity' => $request->quantity,
        'purchase_price' => $book->price,'total' => $total,'wipay_order_id' => $order_id,
        'order_status_id' => OrderStatus::where('name','pending')->first()->id,'user_id' =>$request->user()->id]);
        
        //Wrapping up WiPay vars
        $requestToWipay = [
        'account_number' => $account_number,
        'country_code'=> $country_code,
        'card_type' => $card_type,
        'currency' => $currency,
        'environment' => $environment,
        'fee_structure' => $fee_structure,
        'method' => $method,
        'order_id' => $order_id,
        'origin' => $origin,
        'response_url' => $response_url, 
        'total'=> $total
        ];
        logger('WiPay Payload:');
        logger($requestToWipay);

        //Send out request
        $response = Http::asForm()->withHeaders(['Accept' => 'application/json','Content-Type' => 'application/x-www-form-urlencoded'])
        ->post('https://jm.wipayfinancial.com/plugins/payments/request',$requestToWipay);

        //Returns URL to hosted page 
        $content = $response->json();
        return ($content);
    }

    //Processes WiPay response
    public function process(Request $request)
    {   //Collects Order Status IDs
        $orderSuccessID = OrderStatus::where('name','complete')->first()->id;
        $orderRefundID = OrderStatus::where('name','refund')->first()->id;
        $orderFailedID = OrderStatus::where('name','failed')->first()->id;
        $orderPendingID = OrderStatus::where('name','pending')->first()->id;
        $order = Order::where('wipay_order_id',$request->order_id)->first();

        $apikey = config('services.wipay.key');
        $originalTotal = $order->total;
        $transaction_id = $request->transaction_id;
        $bad_request_message = ['message' => 'Bad Request'];

        //Process legitimacy of transaction
        $calculated_hash = md5($transaction_id.$originalTotal.$apikey);
        logger('hash components: '.$transaction_id.$originalTotal.$apikey);
        logger('Calculated Hash '.$calculated_hash);
        logger('Received Hash '.$request->hash);
        if  ($request->hash != $calculated_hash)
        {
            logger('Calculated hash not matching hash received');
            return response($bad_request_message,400);
        }
        else
        {
            //Checking if the order ID received is in the database
            if ($order->wipay_order_id == $request->order_id)
            {
                //If it is already processed
                if ($order->order_status_id  != $orderPendingID)
                {
                    logger('Order already processed, ie, not found in a pending state');
                    return response($bad_request_message,400);
                }
            }
            else
            {
                logger('Wipay order not in database');
                return response($bad_request_message,400);
            }
        }


        

        $response = [];
        //References the order in the database by the WiPay Order ID
        //Checks book quantity, if quantity is available to sell
        if ($order->book->is_sellable($order->quantity))
        {
            //lowers book inventory on sucess
            if ($request->status == 'success')
            {
                array_push($response,['Message' => 'Success: Transaction was successful']);
                $order->book->quantity -= $order->quantity;
                $order->book->save();
                $order->order_status_id = $orderSuccessID;
                $order->save();
            }
            else if ($request->status == 'failed')
            {
                array_push($response,['Message' => 'Error: Something went wrong. Please Try again later or contact your bank for details']);
                $order->order_status_id = $orderFailedID;
                $order->book->save();
            }
            
        }
        else
        {
            //If book quantity not available, yet the wipay transaction was sucessful, note in system as to be refunded
            if ($request->status == 'success')
            {
                array_push($response,['Message' => 'Error: item was out of stock during the transaction, you will be refunded in 24 hours']);
                $order->order_status_id = $orderRefundID;
                $order->save();
            }
            else if ($request->status == 'failed')
            {
                array_push($response,['Message' => 'Error: Something went wrong. Please Try again later or contact your bank for details']);
                $order->order_status_id = $orderFailedID;
                $order->book->save();
            }
        }
        array_push($response,$request->all());
        
        return $response;
    }
}
