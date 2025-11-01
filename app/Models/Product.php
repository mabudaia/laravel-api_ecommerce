<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    use HasFactory;

   // protected $fillable = ['name', 'description', 'price', 'quantity', 'discount'];
     protected $guarded=['id'];

 
    public function user(){
    return $this->belongsTo(User::class);
    }
    public function categories(){
    return $this->belongsToMany(Category::class);
    }
    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot('quantity', 'price');
    }
//السعر بعد الخصم يُحسب تلقائيًا
    public function getPriceAfterDiscountAttribute()
    {
        return $this->price * (1 - ($this->discount / 100));
    }
    
}


