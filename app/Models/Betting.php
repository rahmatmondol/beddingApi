<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;

class Betting extends Model
{
    use HasFactory;
    protected $fillable = [
        "provider_id",
        "service_id",
        "additional_details",
        "metadata",
        "status",

    ];
    protected $casts = [
        "metadata"=> AsCollection::class,
    ];

    public function provider(){
        return $this->belongsTo(Provider::class);
    }
}
