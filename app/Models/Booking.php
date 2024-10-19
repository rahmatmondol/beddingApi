<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable=[
        'provider_id','customer_id','campaign_id','category_id','service_id','payment_id','payment_method','is_paid','metadata','total_amount','service_charge','provider_service_charge',
    ];
}
