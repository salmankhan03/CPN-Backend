<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    const STATUS_PENDING = "Pending"; // very first stage
    const STATUS_CONFIRMED = "Confirmed"; // accepted by admin
    const STATUS_PROCESSING = "Processing"; // out for delivery
    const STATUS_DELIVERED = "Delivered"; // handed over to customer
    const STATUS_CANCELLED = "Cancelled"; // before delivery or before confirming
    const STATUS_RETURNED = "Returned"; // after delivered
    const STATUS_REFUNDED = "Refunded"; // if payment is not cod , after deleivered the product >> payment will be reverted

    const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_CANCELLED,
        self::STATUS_CONFIRMED,
        self::STATUS_DELIVERED,
        self::STATUS_RETURNED,
        self::STATUS_REFUNDED,
        self::STATUS_PROCESSING,
    ];

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
