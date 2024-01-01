<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponCode extends Model
{

    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $table = 'coupon_code';

    protected $fillable = [
        'code', 'expires_at', 'amount', 'calculation_type', 'minimum_amount', 'product_category_id', 'is_eligible_for_free_shipping'
    ];

    protected $hidden = [
        'deleted_at'
    ];
}