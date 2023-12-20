<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    const STATUS_PENDING = "PENDING";
    const STATUS_CANCELLED = "CANCELLED";
    const STATUS_CONFIRMED = "CONFIRMED";
    const STATUS_DELIVERED = "DELIVERED";

    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'payment_id',
        'total_amount',
        'percent_discount_applied',
        'status',
        'promo_code',
        'is_guest',
        'guest_user_id'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function shippingAddress()
    {
        return $this->hasOne(UserShippingAddress::class);
    }

    public function billignAddress()
    {
        return $this->hasOne(UserBillingAddress::class);
    }

    public function payment()
    {
        return $this->hasOne(Payments::class);
    }
}
