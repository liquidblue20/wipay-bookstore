<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['book_id','quantity','purchase_price','total','wipay_order_id','order_status_id','user_id'];
    
    public function book()
    {
        return $this->belongsTo(Book::class,'book_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class,'order_status_id');
    }


}
