<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Authenticatable;

class Customer extends Model
{
    use HasFactory, HasApiTokens, Authenticatable;

    protected $fillable = [
        'name', 'email','phone', 'password', 'image', 'status',
    ];
    protected $hidden = [
        'password',
        'created_at',
        'updated_at',

    ];
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value != null && file_exists(public_path($value)) ? asset($value) : asset("assets/images/av.png"),
        // set: fn ($value) => strtolower($value),
        );
    }
}
