<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // تحديد الأعمدة القابلة للتعبئة
    protected $fillable = [
        'name',
        'image',
    ];

    public function shops() {
        return $this->hasMany(Shop::class);
    }
    
    public function products() {
        return $this->hasMany(Product::class);
    }
}