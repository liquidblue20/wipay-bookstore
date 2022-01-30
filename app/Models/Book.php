<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable =     [
        'title','author','isbn','quantity','price'
    ];

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function is_sellable($quantity)
    {
        logger('Book Quantity ID'.$this->id.': Quant'.$this->quantity);
        if ($this->quantity < $quantity)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

}
