<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'total_quantity',
        'group_orders_id',
        'status',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function groupOrder() {
        return $this->belongsTo(GroupOrder::class, 'group_orders_id');
    }
    
    public function items() {
        return $this->hasMany(OrderItem::class);
    }
    
    public function payment() {
        return $this->hasOne(Payment::class);
    }
    
    public function delivery() {
        return $this->hasMany(Delivery::class);
    }
}
