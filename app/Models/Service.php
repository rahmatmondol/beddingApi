<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Service extends Model
{
    use HasFactory;

    protected $hidden=[
        'created_at',
        'updated_at',
        'is_featured'
    ];


    protected $fillable = [
        'name',
        'category_id',
        'customer_id',
        'zone',
        'price',
        'price_type',
        'level',
        'currency',
        'commotion',
        'provider_amount',
        'status',
        'skill',
        'description',
        'address',
        'location',
        'image'
    ];

    protected $casts = [
        'zone' => AsCollection::class,
        'skill' => AsCollection::class
    ];

    public function getSkillAttribute($value)
    {
        try {
            // Try to cast the JSON string to an array
            return $this->asCollection($value);
        } catch (\Throwable $e) {
            // Handle the exception or return a default value
            return [];
        }
    }

    public function customer(){

        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'service_id');
    }

    public function bettings()
    {
        return $this->hasMany(Betting::class,'service_id');
    }

    public function images()
    {
        return $this->hasMany(ServiceImage::class);
    }

//    protected function image(): Attribute
//    {
//        return Attribute::make(
//            get: fn ($value) => $value != null && file_exists(public_path($value)) ? asset($value) : asset("assets/images/av.png"),
//
//        );
//    }

//    protected function image(): Attribute
//    {
//        return Attribute::make(
//            get: function ($value) {
//                if ($value !== null && file_exists(public_path($value))) {
//                    return asset($value);
//                }
//                return asset("assets/images/av.png");
//            }
//        );
//    }


    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
