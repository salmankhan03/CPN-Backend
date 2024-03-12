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

    protected $fillable = [
        "order_id",
        "previous_order_status",
        "current_order_status",
        "email_body",
        "from_email",
        "to_email",
        "status_updated_by"
    ];
}
