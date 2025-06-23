<?php
namespace App\Http\Controllers\ShopManger;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\StoreOrUpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
public function index(Request $request)
{
    $user = $request->user();
    $shop = $user->shops; 

    if (!$shop) {
        return response()->json([
            'success' => false,
         'message' => 'This user does not have an associated shop.'
        ], 404);
    }


    $query = Product::where('shop_id', $shop->id);

    
    if ($request->has('product_name')) {
        $name = $request->input('product_name');
        $query->where('product_name', 'like', "%{$name}%");
    }

    
    if ($request->has('price_min')) {
        $query->where('price', '>=', $request->input('price_min'));
    }
    if ($request->has('price_max')) {
        $query->where('price', '<=', $request->input('price_max'));
    }


    $sortOrder = $request->input('sort', 'desc');
    if (!in_array($sortOrder, ['asc', 'desc'])) {
        $sortOrder = 'desc';
    }
    $query->orderBy('created_at', $sortOrder);

    $products = $query->get();

    return response()->json([
        'success' => true,
        'data' => $products
    ]);
}


public function store(StoreOrUpdateProductRequest $request)
{
    $user = $request->user();
    $shop = $user->shops; 

    if (!$shop) {
        return response()->json([
            'success' => false,
          'message' => 'This user does not have an associated shop.'
        ], 404);
    }

    $data = $request->validated();

    $data['shop_id'] = $shop->id;

    if ($shop->status !== 'approved') {
        return response()->json([
            'success' => false,
     'message' => 'You cannot add products to this shop  because its status is not approved.'
        ], 403);
    }

    $data['has_group_purchase'] = $request->input('has_group_purchase', false);

    if ($data['has_group_purchase']) {
        if (empty($data['group_price']) || empty($data['group_min_users'])) {
            return response()->json([
                'success' => false,
                'message' => 'The group price and number of users must be filled in when activating group purchasing.'
            ], 422);
        }
    } else {
        unset($data['group_price'], $data['group_min_users']);
    }

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    $product = Product::create($data);

    return response()->json([
        'success' => true,
        'message' => 'The product was created successfully.',
        'data' => $product
    ], 201);
}
public function show($id)
{
    $product = Product::find($id);
    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }
    return response()->json($product);
}

  public function update(StoreOrUpdateProductRequest $request, Product $product)
{
    $this->authorizeProduct($product);

    $shop = $request->user()->shops;

    if (!$shop || $shop->id !== $product->shop_id || $shop->status !== 'approved') {
        return response()->json([
            'success' => false,
            'message' => 'The product cannot be edited because the store status is not  approved.'
        ], 403);
    }

    $data = $request->validated();

    if ($request->hasFile('image')) {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $data['image'] = $request->file('image')->store('products', 'public');
    }

    $product->update($data);

    $product->refresh();

    return response()->json([
        'success' => true,
        'message' => 'The product was updated successfully.',
        'data' => $product
    ]);
}

public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'The product was successfully deleted.'
        ]);
    }




private function authorizeProduct(Product $product)
{
    $shop = auth()->user()->shops;

    if (!$shop || $product->shop_id !== $shop->id) {
        abort(403, 'You are not authorized to access this product.');
    }
}
}
