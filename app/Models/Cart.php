<?php
/*
فما في داعي نضيف عمود product_id داخل جدول carts
لأن العلاقة بين السلة والمنتجات هي واحد إلى كثير (One to Many)
مش واحد إلى واحد (One to One).*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
         protected $guarded=['id'];

   // protected $fillable = ['user_id', 'product_id', 'quantity'];
    // علاقة مع المنتج

    public function product()
    {
        return $this->belongsToMany(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
{
    return $this->hasMany(CartItem::class);
}

    // حساب الإجمالي لكل عنصر

    public function getTotalAttribute()
    {
        return $this->quantity * $this->product->price_after_discount;
    }
    
}
