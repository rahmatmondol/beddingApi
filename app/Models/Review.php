<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', // Add 'booking_id' to the fillable attributes
        'service_id',
        'provider_id',
        // 'customer_name',
        'customer_id',
        'review_rating',
        'review_comment',
        'booking_date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    public function provider(){
        return $this->belongsTo(Provider::class,'provider_id');
    }
}
