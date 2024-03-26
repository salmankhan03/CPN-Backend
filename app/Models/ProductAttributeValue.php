<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttributeValue extends Model
{

    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $table = 'user_billing_addresses';

    protected $fillable = [
        "name",
        "type", // same as parent type >> dropdoon or radio button
        "status"
    ];

    protected $hidden = [
        'deleted_at'
    ];
}
