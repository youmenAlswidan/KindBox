<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'user_id',
        'shop_name',
        'image_shop',
        'description_shop',
        'location',
        'status',
        'phone_number',
        'category_id',
    ];

    public function user() {
        return $this->belongsTo(User::class );
    }
    
    public function category() {
        return $this->belongsTo(Category::class);
    }
    
    public function products() {
        return $this->hasMany(Product::class);
    }
    
    public function documents() {
        return $this->hasMany(ShopDocument::class);
    }
}