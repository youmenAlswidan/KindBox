<?php

namespace App\Http\Controllers;

use App\Models\GroupOrderMember;
use Illuminate\Http\Request;

class GroupOrderMemberController extends Controller
{
    public function index()
    {
        return response()->json(GroupOrderMember::with(['user', 'groupOrder'])->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'group_order_id' => 'required|exists:group_orders,id',
            'joined_at' => 'required|date',
        ]);

        $member = GroupOrderMember::create($data);

        return response()->json($member, 201);
    }

    public function show(GroupOrderMember $groupOrderMember)
    {
        return response()->json($groupOrderMember->load(['user', 'groupOrder']));
    }

    public function update(Request $request, GroupOrderMember $groupOrderMember)
    {
        $data = $request->validate([
            'joined_at' => 'sometimes|date',
        ]);

        $groupOrderMember->update($data);

        return response()->json($groupOrderMember);
    }

    public function destroy(GroupOrderMember $groupOrderMember)
    {
        $groupOrderMember->delete();

        return response()->json(null, 204);
    }
}