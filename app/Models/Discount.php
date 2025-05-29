<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'discount_value',
        'start_date',
        'end_date',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
    public function GroupOrder() {
        return $this->belongsTo(GroupOrder::class);
    }

}