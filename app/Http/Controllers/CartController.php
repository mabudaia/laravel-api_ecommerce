<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class CartController extends Controller
{

      public function index()
    {
        $cart = Cart::with('product')->get();

        // إعادة البيانات بصيغة JSON
        return response()->json(
            $cart->map(function($item) {
                return [
                    'product_name' => $item->product->name,
                    'price_after_discount' => $item->product->price_after_discount,
                    'quantity' => $item->quantity,
                    'total' => $item->total
                ];
            })
        );
    }

    // إضافة منتج للسلة

   public function store(Request $request)
{
    $user = Auth::user();

    // التحقق من صحة الطلب
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'sometimes|integer|min:1'
    ]);

    // الحصول على السلة الحالية أو إنشاؤها
    $cart = Cart::firstOrCreate(
        ['user_id' => $user->id],
        ['total_price' => 0]
    );

    $product = Product::findOrFail($request->product_id);

    // تحقق إذا المنتج موجود بالفعل بالسلة
    $item = $cart->items()->where('product_id', $product->id)->first();

    if ($item) {
        // زيادة الكمية إذا المنتج موجود
        $item->increment('quantity', $request->quantity ?? 1);
    } else {
        // إضافة المنتج للسلة
        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => $request->quantity ?? 1,
            'unit_price' => $product->price,
        ]);
    }

    // تحديث المجموع الكلي للسلة
    $cart->total_price = $cart->items->sum(fn($i) => $i->quantity * $i->unit_price);
    $cart->save();

    return response()->json($cart->load('items.product'), 201);
}


    

    // حذف عنصر من السلة
    public function destroy(Cart $cart)
    {
        $cart->delete();
        return response()->json(['message' => 'تم حذف العنصر من السلة']);
    }
        // عرض جميع عناصر السلة

    //     public function index()
    // {
    //     // جلب جميع عناصر السلة مع المنتجات المرتبطة
    //     $cart = Cart::with('product')->get();

    //     foreach ($cart as $item) {
    //         echo "المنتج: {$item->product->name} - السعر بعد الخصم: {$item->product->price_after_discount} - الكمية: {$item->quantity} - الإجمالي: {$item->total} <br>";
    //     }
    //         return response()->json($cart);

    // }
}
