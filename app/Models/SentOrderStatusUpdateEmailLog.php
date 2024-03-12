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

    protected $table = 'sent_order_status_update_email_log';

    protected $fillable = [
        "order_id",
        "previous_order_status",
        "current_order_status",
        "email_body",
        "from_email",
        "to_email",
        "updated_by"
    ];
}
