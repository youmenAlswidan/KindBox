<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupOrderMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_order_id',
        'joined_at',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function groupOrder() {
        return $this->belongsTo(GroupOrder::class);
    }
}