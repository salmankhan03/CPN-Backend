<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $table = 'orders';



    protected $fillable = [
        'user_id',
        'payment_id',
        'total_amount',
        'pecent_discount_applied',
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
        return $this->hasOne(UserShippingAddress::class, 'order_id', 'id');
    }

    public function billignAddress()
    {
        return $this->hasOne(UserBillingAddress::class, 'order_id', 'id');
    }
}
