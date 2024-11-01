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
        'zone',
        'price',
        'price_type',
        'currency',
        'skill',
        'description',
        'location',
        'latitude',
        'longitude',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class, 'rivew_id');
    }

    public function bettings()
    {
        return $this->hasMany(Betting::class, 'service_id');
    }

    public function images()
    {
        return $this->hasMany(ServiceImage::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
