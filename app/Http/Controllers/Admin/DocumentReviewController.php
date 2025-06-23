<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopDocument;

class DocumentReviewController extends Controller
{
    public function index()
    {
      
        $allDocuments = ShopDocument::with('shop.user')->get();

      
        $documents = $allDocuments->where('status', 'pending');

        
        $counts = [
            'approved' => $allDocuments->where('status', 'approved')->count(),
            'rejected' => $allDocuments->where('status', 'rejected')->count(),
            'pending'  => $allDocuments->where('status', 'pending')->count(),
        ];

        return view('admin.documents.index', [
            'documents' => $documents,
            'counts' => $counts
        ]);
    }

    public function approve($id)
    {
       
        $document = ShopDocument::findOrFail($id);
        $document->status = 'approved';
        $document->save();

        
        $shop = $document->shop;
        if ($shop) {
            $shop->status = 'approved';
            $shop->save();
        }

       return redirect()->back()->with('success', 'The document has been approved and the store is active.');    }

    public function reject($id)
    {
        $document = ShopDocument::findOrFail($id);
        $document->status = 'rejected';
        $document->save();

           $shop = $document->shop;
        if ($shop) {
            $shop->status = 'rejected'; 
            $shop->save();
        }

        return redirect()->back()->with('success', 'The document was rejected.');
    }

    public function getFile($id)
    {
        $document = ShopDocument::findOrFail($id);
       
        $fileUrl = asset('storage/' . $document->file_path_document);

        return response()->json(['file_url' => $fileUrl]);
    }
}
