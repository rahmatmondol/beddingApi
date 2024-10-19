<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    protected $table = 'carts'; // Set the table name if different from the default
    protected $fillable = ['provider_id', 'service_id', 'count'];
    protected  $hidden= ['created_at','updated_at'];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'user_id');
    }
}
