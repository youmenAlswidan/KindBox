<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopDocument extends Model
{
    use HasFactory;

     protected $fillable = [
        'shop_id',
        'document_type',
        'file_path_document',
        'status',
       
    ];
    public function shop()
{
    return $this->belongsTo(Shop::class);
}

}