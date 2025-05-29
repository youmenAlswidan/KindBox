<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        // جلب كل العناصر في قائمة الرغبات للمستخدمين مع بيانات المنتج
        return response()->json(Wishlist::with('product')->get());
    }

    public function store(Request $request)
    {
        // التحقق من البيانات المدخلة
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        // إضافة المنتج إلى قائمة الرغبات
        $wishlist = Wishlist::create($data);

        return response()->json($wishlist, 201);
    }

    public function show(Wishlist $wishlist)
    {
        // عرض قائمة الرغبات مع بيانات المنتج
        return response()->json($wishlist->load('product'));
    }

    public function destroy(Wishlist $wishlist)
    {
        // حذف عنصر من قائمة الرغبات
        $wishlist->delete();

        return response()->json(null, 204);
    }
}