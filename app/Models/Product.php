<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'product_name',
        'description',
        'price',
        'shop_id',
        'image',
        'has_group_purchase',
        'group_price',
        'group_min_users',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }
    
    public function shop() {
        return $this->belongsTo(Shop::class);
    }
    
    public function discount() {
        return $this->hasOne(Discount::class);
    }
    
    public function groupOrders() {
        return $this->hasOne(GroupOrder::class);
    }
    
    public function wishlists() {
        return $this->hasMany(Wishlist::class);
    }
    
    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
}