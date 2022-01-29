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
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|numeric',
            'quantity' => 'required|numeric',
            'user_id' => 'required|numeric'
        ]);
        // http://127.0.0.1:8001/api/payment_result
        // https://tt.wipayfinancial.com/response/
        $book = Book::find($request->book_id); //Pointless to lock into transaction here as the user will be taken to hosting page,
                                                                                        //where we have to wait an certain period till we receive response at another api endpoint
        if ($book->is_sellable($request->quantity) == False)
        {
            return response()->json(["message" => 'Not enough stock book of the requested book at this time']);
        }
        $total = $request->quantity * $book->price;
        $total = number_format((float)$total, 2, '.', '');

        $account_number = 1234567890;
        $card_type = 'mastercard';
        $currency = 'TTD';
        $environment = 'sandbox';
        $fee_structure = 'customer_pay';
        $method = 'credit_card';
        $order_id = (string) Str::uuid();
        $origin= 'WiPay-example_app';
        $response_url= 'http://127.0.0.1:8001/api/payment_result';
        $country_code='TT';

        $order = Order::create(['book_id' => $book->id, 'quantity' => $request->quantity,
        'purchase_price' => $book->price,'total' => $total,'wipay_order_id' => $order_id,
        'order_status_id' => OrderStatus::where('name','pending')->first()->id,'user_id' =>$request->user_id]);
        
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
        $response = Http::asForm()->withHeaders(['Accept' => 'application/json','Content-Type' => 'application/x-www-form-urlencoded'])
        ->post('https://jm.wipayfinancial.com/plugins/payments/request',$requestToWipay);

        $content = $response->json();
        return ($content);
    

    }

    public function process(Request $request)
    {
        $orderSuccessID = OrderStatus::where('name','complete')->first()->id;
        $orderRefundID = OrderStatus::where('name','refund')->first()->id;
        $orderFailedID = OrderStatus::where('name','failed')->first()->id;
        $order = Order::where('wipay_order_id',$request->order_id)->first();
        if ($order->book->is_sellable($order->quantity))
        {
            if ($request->status == 'success')
            {
                $order->book->quantity -= $order->quantity;
                $order->book->save();
                $order->order_status_id = $orderSuccessID;
                $order->save();
            }
            else if ($request->status == 'failed')
            {
                $order->order_status_id = $orderFailedID;
                $order->book->save();
            }
            
        }
        else
        {
            if ($request->status == 'success')
            {
                $order->order_status_id = $orderRefundID;
                $order->save();
            }
            else if ($request->status == 'failed')
            {
                $order->order_status_id = $orderFailedID;
                $order->book->save();
            }
        }
        return $request->all();
    }
}
