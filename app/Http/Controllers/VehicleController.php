<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        return response()->json(Vehicle::with('user')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_type' => 'required|string',
            'is_available' => 'required|boolean',
            'license_plate' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $vehicle = Vehicle::create($data);

        return response()->json($vehicle, 201);
    }

    public function show(Vehicle $vehicle)
    {
        return response()->json($vehicle->load('user'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'vehicle_type' => 'sometimes|string',
            'is_available' => 'sometimes|boolean',
            'license_plate' => 'sometimes|string',
            'user_id' => 'sometimes|exists:users,id',
        ]);

      
    }
}