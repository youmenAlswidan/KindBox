<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // علاقة مع Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function shops()
    {
         return $this->hasOne(Shop::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function groupOrderMembers()
    {
        return $this->hasMany(GroupOrderMember::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}