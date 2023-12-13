<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserShippingAddress extends Model
{

    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $table = 'user_shipping_addresses';


    protected $fillable = [
        "user_id",
        "guest_user_id",
        "order_id",
        "first_name",
        "last_name",
        "country",
        "company_name",
        "street_address",
        "city",
        "state",
        "zip",
        "phone",
        "email"
    ];

    protected $hidden = [
        'deleted_at'
    ];
}