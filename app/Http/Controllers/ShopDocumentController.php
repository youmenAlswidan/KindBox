<?php

namespace App\Http\Controllers;

use App\Models\ShopDocument;
use Illuminate\Http\Request;

class ShopDocumentController extends Controller
{
    public function index()
    {
        return response()->json(ShopDocument::with('shop')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'status' => 'required|string',
            'document_type' => 'required|string',
            'file_path_document' => 'required|string',
        ]);

        $shopDocument = ShopDocument::create($data);

        return response()->json($shopDocument, 201);
    }

    public function show(ShopDocument $shopDocument)
    {
        return response()->json($shopDocument->load('shop'));
    }

    public function update(Request $request, ShopDocument $shopDocument)
    {
        $data = $request->validate([
            'status' => 'sometimes|string',
            'document_type' => 'sometimes|string',
            'file_path_document' => 'sometimes|string',
        ]);

        $shopDocument->update($data);

        return response()->json($shopDocument);
    }

    public function destroy(ShopDocument $shopDocument)
    {
        $shopDocument->delete();

        return response()->json(null, 204);
    }
}