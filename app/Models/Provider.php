<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;



class Provider extends Model
{
    use HasFactory,HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'zone_id',
        'password',
        'image',
        'rating_count',
        'avg_rating',
        'country',
        'bio',
        'Total_service_count',
    ];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
    ];

    public function cart(){
        return $this->hasMany(Cart::class);
    }

    public function user(){
        return $this->belongsTo(Review::class);
    }
    public function reviews(){

        return $this->hasMany(Review::class);
    }


    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value != null && file_exists(public_path($value)) ? asset($value) : asset("assets/images/av.png"),
        // set: fn ($value) => strtolower($value),
        );
    }
}
