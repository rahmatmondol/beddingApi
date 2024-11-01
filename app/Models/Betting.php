<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;
class Betting extends Model
{
    use HasFactory;
    protected $fillable = [
        "betting_amount",
        "metadata",
        "additional_details",
        "status",
    ];
    protected $casts = [
        "metadata"=> AsCollection::class,
    ];

    // The AsCollection cast will automatically convert the metadata field into a Collection
    // when it is retrieved from the database, and convert it back into a JSON string when
    // it is saved to the database. This allows us to easily access and manipulate the 
    // metadata as an array in our code.

    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
