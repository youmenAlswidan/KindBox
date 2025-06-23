<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreOrUpdateDeliveryRequest;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveries = User::where('role_id', 4)->paginate(10);
        return view('admin.delivery.index', compact('deliveries'));
    }

    public function create()
    {
        return view('admin.delivery.create');
    }

    public function store(StoreOrUpdateDeliveryRequest $request)
    {
        User::create([
            'role_id'      => 4,
            'first_name'   => $request->first_name,
            'last_name'    => $request->last_name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('admin.delivery.index')->with('success', 'تم إنشاء حساب الدليفري بنجاح.');
    }

    public function show($id)
    {
        $delivery = User::where('role_id', 4)->findOrFail($id);
        return view('admin.delivery.show', compact('delivery'));
    }

    public function edit($id)
    {
        $delivery = User::where('role_id', 4)->findOrFail($id);
        return view('admin.delivery.edit', compact('delivery'));
    }

    public function update(StoreOrUpdateDeliveryRequest $request, $id)
    {
        $delivery = User::where('role_id', 4)->findOrFail($id);

        $delivery->first_name   = $request->first_name;
        $delivery->last_name    = $request->last_name;
        $delivery->email        = $request->email;
        $delivery->phone_number = $request->phone_number;

        if ($request->filled('password')) {
            $delivery->password = Hash::make($request->password);
        }

        $delivery->save();

        return redirect()->route('admin.delivery.index')->with('success', 'تم تحديث حساب الدليفري بنجاح.');
    }

    public function destroy($id)
    {
        $delivery = User::where('role_id', 4)->findOrFail($id);
        $delivery->delete();

        return redirect()->route('admin.delivery.index')->with('success', 'تم حذف حساب الدليفري بنجاح.');
    }
}
