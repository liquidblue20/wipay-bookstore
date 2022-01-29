<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Order::all();
    }

    /**
     * Display a single resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return $order;
    }

    public function order_details($id)
    {
        $order = Order::find($id);
        return ['id' => $order->id,'wipay_order_id' => $order->wipay_order_id,'book'=> $order->book,'quantity' => $order->quantity,'total' => $order->total,'user' => $order->user ];
    }

    public function all_order_details()
    {
        $orders = Order::all();
        $result = [];
        foreach ($orders as $order)
        {
          array_push($result,['id' => $order->id,'wipay_order_id' => $order->wipay_order_id,'book'=> $order->book,'quantity' => $order->quantity,'total' => $order->total,'user' => $order->user ]);
        }
        return $result;
    }
}
