<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payments extends Model
{

    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $fillable = [
        "order_id",
        "user_id",
        "is_guest",
        "external_payment_id",
        "payment_gateway_name",
        "type",
        "is_order_cod",
        "is_cod_paymend_received",
        "amount",
        "status",
    ];

    protected $hidden = [
        'deleted_at'
    ];
}
