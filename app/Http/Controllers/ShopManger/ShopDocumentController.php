<?php

namespace App\Http\Controllers\ShopManger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShopDocument;
use App\Models\Shop;
use App\Http\Requests\StoreOrUpdateShopDocumentRequest;
use App\Http\Resources\ShopDocumentResource;

class ShopDocumentController extends Controller
{
    public function index()
    {
        $shop = Shop::where('user_id', auth()->id())->first();

        if (!$shop) {
            return response()->json(['message' => 'You do not have a shop yet.'], 404);
        }

        $documents = $shop->documents;

        return ShopDocumentResource::collection($documents);
    }

    public function store(StoreOrUpdateShopDocumentRequest $request)
    {
        $shop = Shop::where('user_id', auth()->id())->first();

        if (!$shop) {
            return response()->json([
                'message' => 'You need to create a shop first.',
                'user_id' => auth()->id(),
            ]);
        }

        $filePath = $request->file('file_path_document')->store('shop_documents', 'public');

        $document = ShopDocument::create([
            'shop_id' => $shop->id,
            'document_type' => $request->document_type,
            'file_path_document' => $filePath,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'The document has been uploaded and is awaiting admin review.',
            'document' => new ShopDocumentResource($document),
        ]);
    }

    public function show($id)
    {
        $document = ShopDocument::findOrFail($id);
        $this->authorizeAccess($document);

        return new ShopDocumentResource($document);
    }

    public function update(StoreOrUpdateShopDocumentRequest $request, $id)
    {
        $document = ShopDocument::findOrFail($id);
        $this->authorizeAccess($document);

        $document->update($request->only(['document_type']));

        if ($request->hasFile('file_path_document')) {
            $filePath = $request->file('file_path_document')->store('shop_documents', 'public');
            $document->update(['file_path_document' => $filePath]);
        }

        return response()->json([
            'message' => 'The document was updated successfully.',
            'document' => new ShopDocumentResource($document),
        ]);
    }

    public function destroy($id)
    {
        $document = ShopDocument::findOrFail($id);
        $this->authorizeAccess($document);

        $document->delete();

        return response()->json(['message' => 'The document was deleted successfully.']);
    }

    protected function authorizeAccess(ShopDocument $document)
    {
        if ($document->shop->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to access this document.');
        }
    }
}
