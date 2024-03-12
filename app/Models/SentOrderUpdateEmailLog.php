<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SentOrderStatusUpdateEmailLog extends Model
{

    use  SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $table = 'user_billing_addresses';

    protected $fillable = [
        "order_id",
        "current_order_status_id",
        "email_body",
        "from_email",
        "to_email",
        "status_updated_by"
    ];
}