<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::with('role')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'First_name' => 'required|string|max:255',
            'Last_name' => 'required|string|max:255',
            'Email' => 'required|email|unique:users',
            'Password' => 'required|string|min:6',
            'Phone_number' => 'nullable|string',
        ]);
    

        $data['Password'] = bcrypt($data['Password']); // تشفير كلمة السر

        $user = User::create($data);

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return response()->json($user->load('role'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'First_name' => 'required|string|max:255',
            'Last_name' => 'required|string|max:255',
            'Email' => 'required|email|unique:users,Email,' . $user->id,
            'Password' => 'nullable|string|min:6',
            'Phone_number' => 'nullable|string',
        ]);

        if ($request->has('Password')) {
            $data['Password'] = bcrypt($data['Password']); // تشفير كلمة السر
        }

        $user->update($data);

        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }
}