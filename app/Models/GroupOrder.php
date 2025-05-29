<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'status',
        'current_user_count',
        'require_user_count',
        'dead_line',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
    
    public function members() {
        return $this->hasMany(GroupOrderMember::class);
    }
    
    public function orders() {
        return $this->hasMany(Order::class);
    }
}
