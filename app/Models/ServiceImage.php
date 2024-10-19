<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_id',
        'path'
    ];


    protected $hidden = [

        'created_at',
        'updated_at',

    ];

    protected function images(){
        return $this->belongsTo(Service::class);
    }

    protected function path(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value != null && file_exists(public_path($value)) ? asset($value) : asset("assets/images/av.png"),
        // set: fn ($value) => strtolower($value),
        );
    }
}
