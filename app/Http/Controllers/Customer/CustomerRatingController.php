<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Product;
use App\Http\Resources\Customer\CustomerRatingResource;
use App\Http\Requests\CustomerRatingRequest;


class CustomerRatingController extends Controller
{

public function index($productId)
{
    $product = Product::findOrFail($productId);

    // جلب كل التقييمات الخاصة بالمنتج، مع ترتيب أحدث أولاً
    $ratings = $product->ratings()->latest()->get();

    return CustomerRatingResource::collection($ratings);
}




  public function store(CustomerRatingRequest $request, $productId)
{
    $product = Product::findOrFail($productId);

    $rating = new Rating();
    $rating->product_id = $product->id;
    $rating->user_id = $request->user()->id;
    $rating->rating = $request->rating;
    $rating->comment = $request->comment ?? null;
    $rating->save();

    return response()->json([
        'message' => 'تم إضافة التقييم بنجاح',
        'rating' => new CustomerRatingResource($rating),
    ], 201);
}




}
