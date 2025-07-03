<?php
namespace App\Http\Controllers\ShopManger;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\GroupOrder;
use App\Models\Discount;
use App\Http\Requests\StoreOrUpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Controllers\ShopManger\DiscountController;
use App\Http\Requests\StoreOrUpdateDiscountRequest;
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
            $query->where('product_name', 'like', "%" . $request->input('product_name') . "%");
        }

        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        $sortOrder = $request->input('sort', 'desc');
        $query->orderBy('created_at', $sortOrder);

        $products = $query->get();

        return ProductResource::collection($products);
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

        if ($shop->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'You cannot add products to this shop because its status is not approved.'
            ], 403);
        }

        $data = $request->validated();
        $data['shop_id'] = $shop->id;
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

        if ($product->has_group_purchase) {
            GroupOrder::create([
                'product_id' => $product->id,
                'status' => 'open', 
                'current_user_count' => 0,
                'require_user_count' => $product->group_min_users,
                'dead_line' => $request->input('dead_line'), 
            ]);
        }

        return (new ProductResource($product))->additional([
            'success' => true,
            'message' => 'The product was created successfully.'
        ]);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return new ProductResource($product);
    }



public function update(StoreOrUpdateProductRequest $request, Product $product)
{
    $this->authorizeProduct($product);

    $shop = $request->user()->shops->firstWhere('id', $product->shop_id);

    if (!$shop || $shop->status !== 'approved') {
        return response()->json([
            'success' => false,
            'message' => 'The product cannot be edited because the store status is not approved.'
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

   
    if ($product->has_group_purchase) {
    $groupOrder = $product->groupOrders()->first();
    
    $deadLine = $request->input('dead_line', $groupOrder?->dead_line);

    if (!$deadLine) {
        return response()->json([
            'success' => false,
            'message' => 'The group purchase deadline is required.',
        ], 422);
    }

    if ($groupOrder) {
        $groupOrder->update([
            'require_user_count' => $product->group_min_users,
            'dead_line' => $deadLine,
        ]);
    } else {
        GroupOrder::create([
            'product_id' => $product->id,
            'status' => 'open',
            'current_user_count' => 0,
            'require_user_count' => $product->group_min_users,
            'dead_line' => $deadLine,
        ]);
 
            
        }

        
    } 
    else {
  
    $product->update([
        'group_price' => null,
        'group_min_users' => null,
    ]);

    $product->groupOrders()->delete(); 
}

  
if ($request->has('remove_discount') && $request->boolean('remove_discount')) {
    if ($product->discount) {
        $product->discount()->delete();
    }
} elseif ($request->filled(['discount_value', 'start_date', 'end_date'])) {
    $discountData = [
        'discount_value' => $request->discount_value,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
    ];

    if ($product->discount) {
        $product->discount()->update($discountData);
    } else {
        $product->discount()->create($discountData);
    }
}

return (new ProductResource($product))->additional([
    'success' => true,
    'message' => 'The product was updated successfully.'
]);

   
}


    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        
        $product->groupOrders()->delete();

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
