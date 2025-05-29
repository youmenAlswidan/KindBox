<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        // جلب كل الخصومات مع بيانات المنتج المرتبط
        return response()->json(Discount::with('product')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_value' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $discount = Discount::create($data);

        return response()->json($discount->load('product'), 201);
    }

    public function show(Discount $discount)
    {
        // جلب خصم واحد مع بيانات المنتج المرتبط
        return response()->json($discount->load('product'));
    }

    public function update(Request $request, Discount $discount)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_value' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $discount->update($data);

        return response()->json($discount->load('product'));
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();

        return response()->json(null, 204);
    }
}