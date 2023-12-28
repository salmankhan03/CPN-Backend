<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    const STATUS_PENDING = "Pending";
    const STATUS_CANCELLED = "Cancelled";
    const STATUS_CONFIRMED = "Confirmed";
    const STATUS_DELIVERED = "Delivered";
    const STATUS_RETURNED = "Returned";
    const STATUS_REFUNDED = "Refunded";

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

    public function billingAddress()
    {
        return $this->hasOne(UserBillingAddress::class);
    }

    public function payment()
    {
        return $this->hasOne(Payments::class);
    }

    public function Items()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }
}
