<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'is_active', 'is_featured', 'zone_id', 'image'];

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }


    protected function image(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value !== null && file_exists(public_path($value))) {
                    return asset($value);
                }
                return asset("assets/images/av.png");
            }
        );
    }
}
