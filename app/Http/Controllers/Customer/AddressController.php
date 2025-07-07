<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderAddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Models\ShippingCost;

class AddressController extends Controller
{
 public function storeAddressForOrder(StoreOrderAddressRequest $request)
{
    $shippingCostRecord = ShippingCost::where('city', $request->city)->first();

    if (!$shippingCostRecord) {
        return response()->json([
            'message' => 'تكلفة الشحن للمدينة المحددة غير موجودة',
        ], 404);
    }

    // جلب البيانات الموثوقة بعد التحقق من صلاحيتها
    $validated = $request->validated();
    $validated['user_id'] = auth()->id();

    // حفظ العنوان في قاعدة البيانات
    $address = Address::create($validated);

    $shippingCost = $shippingCostRecord->cost;

    return response()->json([
        'message' => 'تم حفظ العنوان بنجاح',
        'address' => $address,
        'shipping_cost' => $shippingCost,
    ]);
}



}
